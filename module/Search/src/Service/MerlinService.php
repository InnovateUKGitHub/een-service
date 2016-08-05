<?php

namespace Search\Service;

use Console\Service\ImportService;
use Zend\Json\Server\Exception\HttpException;

class MerlinService
{
    /** @var ImportService */
    private $service;

    /**
     * @param ImportService $service
     */
    public function __construct(ImportService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getOpportunities($id = null)
    {
        $results = $this->service->getData('all');

        if ($id === null) {
            return $this->getFirst10Opportunities($results);
        }

        return $this->searchOpportunities($results, $id);
    }

    /**
     * @param \SimpleXMLElement $results
     *
     * @return array
     */
    public function getFirst10Opportunities(\SimpleXMLElement $results)
    {
        $opportunities = [];
        $i = 0;
        foreach ($results->profile as $profile) {
            $opportunities[] = [
                'id'                 => (string)$profile->reference->external,
                'title'              => (string)$profile->content->title,
                'summary'            => (string)$profile->content->summary,
                'description'        => (string)$profile->content->description,
                'partner_expertise'  => (string)$profile->cooperation->partner->area,
                'stage'              => (string)$profile->cooperation->stagedev->stage,
                'ipr'                => (string)$profile->cooperation->ipr->status,
                'ipr_comment'        => (string)$profile->cooperation->title->comment,
                'country_code'       => (string)$profile->company->country->key,
                'country'            => (string)$profile->company->country->label,
                'date'               => (string)$profile->datum->update,
                'deadline'           => (string)$profile->datum->deadline,
                'partnership_sought' => $this->extractPartnerships($profile->partnerships),
                'industries'         => $this->extractIndustries($profile->cooperation->exploitations),
                'technologies'       => $this->extractTechnologies($profile->keyword->technologies),
                'commercials'        => $this->extractCommercials($profile->keyword->naces),
                'markets'            => $this->extractMarkets($profile->keyword->markets),
                'eoi'                => (bool)$profile->eoi->status,
                'advantage'          => (string)$profile->cooperation->plusvalue,
            ];
            $i++;

            if ($i > 10) {
                return $opportunities;
            }
        }

        return $opportunities;
    }

    /**
     * @param \SimpleXMLElement $partnerships
     *
     * @return array
     */
    private function extractPartnerships(\SimpleXMLElement $partnerships)
    {
        $result = [];
        foreach ($partnerships->string as $partnership) {
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
        foreach ($industries->exploitation as $industry) {
            if ((string)$industry->label) {
                $result[] = (string)$industry->label;
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
        foreach ($technologies->technologies as $technology) {
            if ((string)$technology->label) {
                $result[] = (string)$technology->label;
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
        foreach ($commercials->nace as $commercial) {
            if ((string)$commercial->label) {
                $result[] = (string)$commercial->label;
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
        foreach ($markets->market as $market) {
            if ((string)$market->label) {
                $result[] = (string)$market->label;
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $results
     * @param int $id
     *
     * @return array
     */
    public function searchOpportunities(\SimpleXMLElement $results, $id)
    {
        foreach ($results->profile as $profile) {
            if ((string)$profile->reference->external === $id) {
                return [
                    'id'                 => (string)$profile->reference->external,
                    'title'              => (string)$profile->content->title,
                    'summary'            => (string)$profile->content->summary,
                    'description'        => (string)$profile->content->description,
                    'partner_expertise'  => (string)$profile->cooperation->partner->area,
                    'stage'              => (string)$profile->cooperation->stagedev->stage,
                    'ipr'                => (string)$profile->cooperation->ipr->status,
                    'ipr_comment'        => (string)$profile->cooperation->title->comment,
                    'country_code'       => (string)$profile->company->country->key,
                    'country'            => (string)$profile->company->country->label,
                    'date'               => (string)$profile->datum->update,
                    'deadline'           => (string)$profile->datum->deadline,
                    'partnership_sought' => $this->extractPartnerships($profile->partnerships),
                    'industries'         => $this->extractIndustries($profile->cooperation->exploitations),
                    'technologies'       => $this->extractTechnologies($profile->keyword->technologies),
                    'commercials'        => $this->extractCommercials($profile->keyword->naces),
                    'markets'            => $this->extractMarkets($profile->keyword->markets),
                    'eoi'                => (bool)$profile->eoi->status,
                    'advantage'          => (string)$profile->cooperation->plusvalue,
                ];
            }
        }
        throw new HttpException('The opportunity does not exists', 404);
    }
}
