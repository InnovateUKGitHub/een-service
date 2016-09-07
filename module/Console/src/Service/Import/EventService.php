<?php

namespace Console\Service\Import;

use Console\Service\IndexService;
use Console\Service\Merlin\EventMerlin;
use Console\Validator\MerlinValidator;

class EventService
{
    /** @var IndexService */
    private $indexService;
    /** @var MerlinValidator */
    private $merlinValidator;
    /** @var EventMerlin */
    private $merlinData;

    /** @var array */
    private $structure;

    /**
     * EventService constructor.
     *
     * @param IndexService    $indexService
     * @param EventMerlin     $merlinData
     * @param MerlinValidator $merlinValidator
     * @param array           $structure
     */
    public function __construct(
        IndexService $indexService,
        EventMerlin $merlinData,
        MerlinValidator $merlinValidator,
        $structure
    )
    {
        $this->indexService = $indexService;
        $this->merlinData = $merlinData;
        $this->merlinValidator = $merlinValidator;
        $this->structure = $structure;
    }

    public function import($month, $type)
    {
        $results = $this->merlinData->getList($month, $type);

        $this->indexService->createIndex(ES_INDEX_EVENT);

        $this->merlinValidator->checkEventsExists($results);

        $dateImport = (new \DateTime())->format('Ymd');
        foreach ($results->{'event'} as $event) {
            $this->merlinValidator->checkDataExists($event, $this->structure);
        }
    }

    public function delete($since, \DateTime $now)
    {
    }
}