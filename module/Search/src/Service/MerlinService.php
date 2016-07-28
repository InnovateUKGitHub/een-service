<?php

namespace Search\Service;

use Console\Service\ImportService;
use JsonSchema\Exception\ResourceNotFoundException;

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

    public function getOpportunities($id)
    {
        // TODO Get the data
        $results = $this->service->getData('all');

        // TODO Search the data
        return $this->searchOpportunities($results, $id);
    }

    public function searchOpportunities($results, $id)
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
        throw new ResourceNotFoundException('The opportunity does not exists', 404);
    }

    private function extractPartnerships($partnerships)
    {
        $result = [];
        foreach ($partnerships->string as $partnership) {
            $result[] = (string)$partnership;
        }

        return $result;
    }

    private function extractIndustries($industries)
    {
        $result = [];
        foreach ($industries->exploitation as $industry) {
            if ((string)$industry->label) {
                $result[] = (string)$industry->label;
            }
        }

        return $result;
    }

    private function extractTechnologies($technologies)
    {
        $result = [];
        foreach ($technologies->technologies as $technology) {
            if ((string)$technology->label) {
                $result[] = (string)$technology->label;
            }
        }

        return $result;
    }

    private function extractCommercials($commercials)
    {
        $result = [];
        foreach ($commercials->nace as $commercial) {
            if ((string)$commercial->label) {
                $result[] = (string)$commercial->label;
            }
        }

        return $result;
    }

    private function extractMarkets($markets)
    {
        $result = [];
        foreach ($markets->market as $market) {
            if ((string)$market->label) {
                $result[] = (string)$market->label;
            }
        }

        return $result;
    }
}
