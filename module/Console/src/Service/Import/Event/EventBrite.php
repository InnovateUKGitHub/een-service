<?php

namespace Console\Service\Import\Event;

use Console\Service\HttpService;
use Zend\Http\Request;
use Zend\Log\Logger;

class EventBrite
{
    const EVENTS_PATH = 'events-path';

    /** @var HttpService */
    private $client;
    /** @var Logger */
    private $logger;

    public function __construct(HttpService $client, Logger $logger, $config)
    {
        $this->client = $client;
        $this->logger = $logger;

        $this->eventsPath = $config[self::EVENTS_PATH];
    }

    public function getList()
    {
        $results = $content = json_decode($this->client->execute(Request::METHOD_GET, $this->eventsPath), true);
        var_dump(array_keys($results['events']));
    }
}