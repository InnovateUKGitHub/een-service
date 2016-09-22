<?php

namespace Console\Factory\Service\Event;

use Common\Factory\HttpServiceFactory;
use Console\Service\Event\EventBrite;
use Console\Service\IndexService;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class EventBriteFactory
{
    const CONFIG = 'config';

    const EVENT_BRITE = 'event-brite';
    const SCHEME = 'scheme';
    const SERVER = 'server';
    const OAUTH_TOKEN = 'oauth-token';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventBrite
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $client = (new HttpServiceFactory())->__invoke($serviceManager);
        $logger = $serviceManager->get(Logger::class);
        $indexService = $serviceManager->get(IndexService::class);
        $config = $serviceManager->get(self::CONFIG);

        if (array_key_exists(self::EVENT_BRITE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the event-brite data');
        }

        $client->setScheme($config[self::EVENT_BRITE][self::SCHEME]);
        $client->setServer($config[self::EVENT_BRITE][self::SERVER]);

        $client->setHeaders(
            [
                'Authorization' => 'Bearer ' . $config[self::EVENT_BRITE][self::OAUTH_TOKEN],
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ]
        );

        return new EventBrite($client, $indexService, $logger, $config[self::EVENT_BRITE]);
    }
}
