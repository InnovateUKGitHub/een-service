<?php

namespace Console\Service\Import;

use Console\Service\Import\Event\MerlinIngest;
use Console\Service\IndexService;
use Console\Service\Merlin\EventMerlin;

class EventService
{
    /** @var IndexService */
    private $indexService;
    /** @var EventMerlin */
    private $merlinData;
    /** @var MerlinIngest */
    private $merlinIngest;

    /**
     * EventService constructor.
     *
     * @param IndexService $indexService
     * @param EventMerlin  $merlinData
     * @param MerlinIngest $merlinIngest
     */
    public function __construct(
        IndexService $indexService,
        EventMerlin $merlinData,
        MerlinIngest $merlinIngest
    )
    {
        $this->indexService = $indexService;
        $this->merlinData = $merlinData;
        $this->merlinIngest = $merlinIngest;
    }

    public function import()
    {
        $this->indexService->createIndex(ES_INDEX_EVENT);

        $dateImport = (new \DateTime())->format('Ymd');

        $this->merlinIngest->import($this->merlinData->getList(), $dateImport);
    }

    public function delete(\DateTime $now)
    {
        $results = $this->indexService->getAll(
            ES_INDEX_EVENT,
            ES_TYPE_EVENT,
            ['date_import']
        );

        $dateImport = $now->format('Ymd');
        $body = [];
        foreach ($results['hits']['hits'] as $document) {
            if ($document['_source']['date_import'] < $dateImport) {
                $body['body'][] = [
                    'delete' => [
                        '_index' => ES_INDEX_EVENT,
                        '_type'  => ES_TYPE_EVENT,
                        '_id'    => $document['_id'],
                    ],
                ];
            }
        }

        if (empty($body)) {
            return;
        }

        $this->indexService->delete($body);
    }
}