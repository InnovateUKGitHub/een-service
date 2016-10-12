<?php

namespace Console\Service\Event;

use Common\Constant\EEN;
use Console\Service\IndexService;

class EventService
{
    /** @var IndexService */
    private $indexService;
    /** @var Merlin */
    private $merlin;
    /** @var EventBrite */
    private $eventBrite;
    /** @var SalesForce */
    private $salesForce;

    /**
     * EventService constructor.
     *
     * @param IndexService $indexService
     * @param Merlin       $merlin
     * @param EventBrite   $eventBrite
     * @param SalesForce   $salesForce
     */
    public function __construct(
        IndexService $indexService,
        Merlin $merlin,
        EventBrite $eventBrite,
        SalesForce $salesForce
    )
    {
        $this->indexService = $indexService;
        $this->merlin = $merlin;
        $this->eventBrite = $eventBrite;
        $this->salesForce = $salesForce;
    }

    public function import()
    {
        $this->indexService->createIndex(EEN::ES_INDEX_EVENT);

        $dateImport = (new \DateTime())->format('Ymd');

        $this->merlin->import($dateImport);
        $this->eventBrite->import($dateImport);
        $this->salesForce->import($dateImport);
    }

    /**
     * @param \DateTime $now
     */
    public function delete(\DateTime $now)
    {
        $results = $this->indexService->getOutOfDateData(
            EEN::ES_INDEX_EVENT,
            EEN::ES_TYPE_EVENT,
            $now->format(EEN::DATE_FORMAT_IMPORT)
        );

        $body = [];
        foreach ($results['hits']['hits'] as $document) {
            $body['body'][] = [
                'delete' => [
                    '_index' => EEN::ES_INDEX_EVENT,
                    '_type'  => EEN::ES_TYPE_EVENT,
                    '_id'    => $document['_id'],
                ],
            ];
        }

        if (empty($body)) {
            return;
        }

        $this->indexService->delete($body);
    }
}