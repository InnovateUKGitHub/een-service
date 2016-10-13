<?php

namespace Console\Service\Event;

use Common\Constant\EEN;
use Common\Service\HttpService;
use Console\Service\IndexService;
use Zend\Http\Request;
use Zend\Log\Logger;

class EventBrite
{
    /** @var HttpService */
    private $client;
    /** @var IndexService */
    private $indexService;
    /** @var Logger */
    private $logger;

    /**
     * EventBrite constructor.
     *
     * @param HttpService  $client
     * @param IndexService $indexService
     * @param Logger       $logger
     * @param string       $eventPath
     */
    public function __construct(HttpService $client, IndexService $indexService, Logger $logger, $eventPath)
    {
        $this->client = $client;
        $this->indexService = $indexService;
        $this->logger = $logger;

        $this->eventsPath = $eventPath;
    }

    /**
     * @param string $dateImport
     */
    public function import($dateImport)
    {
        $results = $this->client->execute(Request::METHOD_GET, $this->eventsPath);

        foreach ($results['events'] as $event) {
            $params = [
                'title'        => $event['name']['text'],
                'summary'      => $event['description']['text'],
                'description'  => $event['description']['text'],
                'start_date'   => $event['start']['utc'],
                'end_date'     => $event['end']['utc'],
                'url'          => $event['url'],
                'country_code' => 'GB',
                'country'      => 'United Kingdom',
                'type'         => 'eventBrite',
                'date_import'  => $dateImport,
            ];

            $this->indexService->index(
                $params,
                $event['id'],
                EEN::ES_INDEX_EVENT,
                EEN::ES_TYPE_EVENT
            );
        }
    }
}