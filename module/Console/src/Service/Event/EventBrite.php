<?php

namespace Console\Service\Event;

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
        $results = $content = $this->client->execute(Request::METHOD_GET, $this->eventsPath);

        foreach ($results['events'] as $event) {
            $params = [
                'id'           => $event['id'],
                'title'        => $event['name']['text'],
                'description'  => $event['description']['text'],
                'url'          => $event['url'],
                'start_date'   => $event['start']['utc'],
                'end_date'     => $event['end']['utc'],
                'status'       => $event['status'],
                'created'      => $event['created'],
                'date_import'  => $dateImport,
                'country_code' => 'GB',
                'country'      => 'United Kingdom',
                'type'         => 'eventbrite',
            ];

            $this->indexService->index(
                $params,
                $event['id'],
                ES_INDEX_EVENT,
                ES_TYPE_EVENT
            );
        }
    }
}