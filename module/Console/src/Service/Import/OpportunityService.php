<?php

namespace Console\Service\Import;

use Console\Service\IndexService;
use Console\Service\Merlin\OpportunityMerlin;
use Console\Validator\MerlinValidator;

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
    }

    /**
     * @param string    $since
     * @param \DateTime $now
     */
    public function delete($since, \DateTime $now)
    {
        $results = $this->indexService->getAll(
            ES_INDEX_OPPORTUNITY,
            ES_TYPE_OPPORTUNITY,
            ['date', 'deadline', 'date_import']
        );

        $dateImport = $now->format('Ymd');
        $dateSince = $now->sub(new \DateInterval('P' . $since . 'M'))->format('Ymd');
        $body = [];
        foreach ($results['hits']['hits'] as $document) {
            if ($document['_source']['date_import'] < $dateImport ||
                $document['_source']['date'] < $dateSince
            ) {
                $body['body'][] = [
                    'delete' => [
                        '_index' => ES_INDEX_OPPORTUNITY,
                        '_type'  => ES_TYPE_OPPORTUNITY,
                        '_id'    => $document['_source']['id'],
                    ],
                ];
            }
        }

        if (empty($body)) {
            return;
        }

        $this->indexService->delete($body);
    }

    public function import($month, $type)
    {
        $results = $this->merlinData->getList($month, $type);

        $this->indexService->createIndex(ES_INDEX_OPPORTUNITY);

        $this->merlinValidator->checkProfilesExists($results);

        $dateImport = (new \DateTime())->format('Ymd');
        foreach ($results->{'profile'} as $profile) {
            $this->merlinValidator->checkDataExists($profile, $this->structure);

            $reference = $profile->{'reference'};
            $content = $profile->{'content'};
            $cooperation = $profile->{'cooperation'};
            $company = $profile->{'company'};
            $datum = $profile->{'datum'};
            $keyword = $profile->{'keyword'};

            $id = (string)$reference->{'external'}->__toString();

            $params = [
                'id'                 => $id,
                'type'               => (string)$reference->{'type'}->__toString(),
                'title'              => (string)$content->{'title'}->__toString(),
                'summary'            => (string)$content->{'summary'}->__toString(),
                'description'        => (string)$content->{'description'}->__toString(),
                'partner_expertise'  => (string)$cooperation->{'partner'}->{'area'}->__toString(),
                'stage'              => (string)$cooperation->{'stagedev'}->{'stage'}->__toString(),
                'ipr'                => (string)$cooperation->{'ipr'}->{'status'}->__toString(),
                'ipr_comment'        => (string)$cooperation->{'ipr'}->{'comment'}->__toString(),
                'country_code'       => (string)$company->{'country'}->{'key'}->__toString(),
                'country'            => (string)$company->{'country'}->{'label'}->__toString(),
                'date_create'        => (string)$datum->{'submit'}->__toString() ?: null,
                'date'               => (string)$datum->{'update'}->__toString() ?: null,
                'deadline'           => (string)$datum->{'deadline'}->__toString() ?: null,
                'partnership_sought' => $this->extractPartnerships($profile->{'partnerships'}),
                'industries'         => $this->extractIndustries($cooperation->{'exploitations'}),
                'technologies'       => $this->extractTechnologies($keyword->{'technologies'}),
                'commercials'        => $this->extractCommercials($keyword->{'naces'}),
                'markets'            => $this->extractMarkets($keyword->{'markets'}),
                'eoi'                => (bool)$profile->{'eoi'}->{'status'}->__toString(),
                'advantage'          => (string)$cooperation->{'plusvalue'}->__toString(),
                'date_import'        => $dateImport,
            ];

            $this->indexService->index(
                $params,
                $id,
                ES_INDEX_OPPORTUNITY,
                ES_TYPE_OPPORTUNITY
            );
        }
    }

    /**
     * @param \SimpleXMLElement $partnerships
     *
     * @return array
     */
    private function extractPartnerships(\SimpleXMLElement $partnerships)
    {
        $result = [];
        foreach ($partnerships->{'string'} as $partnership) {
            $result[] = (string)$partnership;
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $industries
     *
     * @return array
     */
    private function extractIndustries(\SimpleXMLElement $industries)
    {
        $result = [];
        foreach ($industries->{'exploitation'} as $industry) {
            if ((string)$industry->{'other'}) {
                $result[] = (string)$industry->{'other'};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $technologies
     *
     * @return array
     */
    private function extractTechnologies(\SimpleXMLElement $technologies)
    {
        $result = [];
        foreach ($technologies->{'technology'} as $technology) {
            if ((string)$technology->{'label'}) {
                $result[] = (string)$technology->{'label'};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $commercials
     *
     * @return array
     */
    private function extractCommercials(\SimpleXMLElement $commercials)
    {
        $result = [];
        foreach ($commercials->{'nace'} as $commercial) {
            if ((string)$commercial->{'label'}) {
                $result[] = (string)$commercial->{'label'};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $markets
     *
     * @return array
     */
    private function extractMarkets(\SimpleXMLElement $markets)
    {
        $result = [];
        foreach ($markets->{'market'} as $market) {
            if ((string)$market->{'label'}) {
                $result[] = (string)$market->{'label'};
            }
        }

        return $result;
    }
}