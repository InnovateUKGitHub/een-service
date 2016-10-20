<?php

namespace Sync\Service\Opportunity;

use Common\Constant\EEN;
use Sync\Service\IndexService;
use Sync\Validator\MerlinValidator;
use Zend\Escaper\Escaper;

class OpportunityService
{
    /** @var IndexService */
    private $indexService;
    /** @var OpportunityMerlin */
    private $merlinData;
    /** @var MerlinValidator */
    private $merlinValidator;
    /** @var array */
    private $structure;
    /** @var \HTMLPurifier */
    private $purifier;
    /** @var Escaper */
    private $escaper;

    /**
     * OpportunityService constructor.
     *
     * @param IndexService      $indexService
     * @param OpportunityMerlin $merlinData
     * @param MerlinValidator   $merlinValidator
     * @param array             $structure
     */
    public function __construct(
        IndexService $indexService,
        OpportunityMerlin $merlinData,
        MerlinValidator $merlinValidator,
        $structure
    )
    {
        $this->indexService = $indexService;
        $this->merlinData = $merlinData;
        $this->merlinValidator = $merlinValidator;
        $this->structure = $structure;

        $this->purifier = new \HTMLPurifier();
        $this->escaper = new Escaper();
    }

    /**
     * @param \DateTime $now
     */
    public function delete(\DateTime $now)
    {
        $body = [];

        // Get all the out of date opportunities
        $results = $this->indexService->getOutOfDateData(
            EEN::ES_INDEX_OPPORTUNITY,
            EEN::ES_TYPE_OPPORTUNITY,
            $now->format(EEN::DATE_FORMAT_IMPORT)
        );
        foreach ($results['hits']['hits'] as $document) {
            $body['body'][] = [
                'delete' => [
                    '_index' => EEN::ES_INDEX_OPPORTUNITY,
                    '_type'  => EEN::ES_TYPE_OPPORTUNITY,
                    '_id'    => $document['_id'],
                ],
            ];
        }

        // Get all the out of date country
        $results = $this->indexService->getOutOfDateData(
            EEN::ES_INDEX_COUNTRY,
            EEN::ES_TYPE_COUNTRY,
            $now->format(EEN::DATE_FORMAT_IMPORT)
        );
        foreach ($results['hits']['hits'] as $document) {
            $body['body'][] = [
                'delete' => [
                    '_index' => EEN::ES_INDEX_COUNTRY,
                    '_type'  => EEN::ES_TYPE_COUNTRY,
                    '_id'    => $document['_id'],
                ],
            ];
        }

        if (empty($body)) {
            return;
        }

        $this->indexService->delete($body);
    }

    /**
     * @param string $month
     */
    public function import($month)
    {
        $results = $this->merlinData->getList($month);

        $this->indexService->createIndex(EEN::ES_INDEX_OPPORTUNITY);
        $this->indexService->createIndex(EEN::ES_INDEX_COUNTRY);

        $this->merlinValidator->checkProfilesExists($results);

        $dateImport = (new \DateTime())->format(EEN::DATE_FORMAT_IMPORT);
        foreach ($results->{'profile'} as $profile) {
            $this->merlinValidator->checkDataExists($profile, $this->structure);

            $reference = $profile->{'reference'};
            $content = $profile->{'content'};
            $cooperation = $profile->{'cooperation'};
            $company = $profile->{'company'};
            $datum = $profile->{'datum'};

            $params = [
                'type'              => (string)$reference->{'type'},
                'title'             => (string)$content->{'title'},
                'summary'           => $this->purify($content->{'summary'}),
                'description'       => $this->purify($content->{'description'}),
                'partner_expertise' => $this->purify($cooperation->{'partner'}->{'area'}),
                'advantage'         => $this->purify($cooperation->{'plusvalue'}),
                'stage'             => $this->purify($cooperation->{'stagedev'}->{'stage'}),
                'ipr'               => (string)$cooperation->{'ipr'}->{'status'},
                'ipr_comment'       => (string)$cooperation->{'ipr'}->{'comment'},
                'country_code'      => (string)$company->{'country'}->{'key'},
                'country'           => (string)$company->{'country'}->{'label'},
                'date_create'       => (string)$datum->{'submit'} ?: null,
                'date'              => (string)$datum->{'update'} ?: null,
                'deadline'          => (string)$datum->{'deadline'} ?: null,
                'date_import'       => $dateImport,
            ];

            // Import opportunities
            $this->indexService->index(
                $params,
                (string)$reference->{'external'},
                EEN::ES_INDEX_OPPORTUNITY,
                EEN::ES_TYPE_OPPORTUNITY
            );

            // Import countries
            if (!empty($params['country_code'])) {
                $this->indexService->index(
                    [
                        'name'        => $params['country'],
                        'date_import' => $dateImport,
                    ],
                    $params['country_code'],
                    EEN::ES_INDEX_COUNTRY,
                    EEN::ES_TYPE_COUNTRY
                );
            }
        }
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function purify($text)
    {
        $text = $this->escaper->escapeHtml($text);

        $paragraphs = explode("\n", $text);
        $result = '';
        foreach ($paragraphs as $paragraph) {
            if (trim($paragraph) != '') {
                $result .= '<p>' . $paragraph . '</p>';
            }
        }

        return $this->purifier->purify($result);
    }
}