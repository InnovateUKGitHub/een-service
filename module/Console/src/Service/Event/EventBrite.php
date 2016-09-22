<?php

namespace Console\Service\Event;

use Common\Service\HttpService;
use Console\Service\IndexService;
use Zend\Http\Request;
use Zend\Log\Logger;

class EventBrite
{
    const EVENTS_PATH = 'events-path';

    /** @var HttpService */
    private $client;
    /** @var IndexService */
    private $indexService;
    /** @var Logger */
    private $logger;

    public function __construct(HttpService $client, IndexService $indexService, Logger $logger, $config)
    {
        $this->client = $client;
        $this->indexService = $indexService;
        $this->logger = $logger;

        $this->eventsPath = $config[self::EVENTS_PATH];
    }

    public function import($dateImport)
    {
        $results = $content = json_decode($this->client->execute(Request::METHOD_GET, $this->eventsPath), true);

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