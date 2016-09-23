<?php

namespace Console\Factory\Service\Event;

use Common\Constant\EEN;
use Common\Factory\HttpServiceFactory;
use Console\Service\Event\EventBrite;
use Console\Service\IndexService;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class EventBriteFactory
{

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
        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        $config = $config[EEN::EVENT_BRITE];

        $client->setScheme($config[EEN::SCHEME]);
        $client->setServer($config[EEN::SERVER]);

        $client->setHeaders(
            [
                'Authorization'   => 'Bearer ' . $config[EEN::TOKEN],
                'Content-Type'    => 'application/json',
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'application/gzip',
            ]
        );

        return new EventBrite($client, $indexService, $logger, $config[EEN::PATH_EVENT]);
    }

    /**
     * @param array $config
     */
    private function checkRequiredConfig($config)
    {
        if (array_key_exists(EEN::EVENT_BRITE, $config) === false) {
            throw new \InvalidArgumentException(
                'The config file is incorrect. Please specify the event-brite data'
            );
        }
        if (array_key_exists(EEN::SERVER, $config[EEN::EVENT_BRITE]) === false) {
            throw new \InvalidArgumentException(
                'The config file is incorrect. Please specify the event-brite server'
            );
        }
        if (array_key_exists(EEN::SCHEME, $config[EEN::EVENT_BRITE]) === false) {
            throw new \InvalidArgumentException(
                'The config file is incorrect. Please specify the event-brite scheme'
            );
        }
        if (array_key_exists(EEN::TOKEN, $config[EEN::EVENT_BRITE]) === false) {
            throw new \InvalidArgumentException(
                'The config file is incorrect. Please specify the event-brite token'
            );
        }
        if (array_key_exists(EEN::PATH_EVENT, $config[EEN::EVENT_BRITE]) === false) {
            throw new \InvalidArgumentException(
                'The config file is incorrect. Please specify the event-brite path'
            );
        }
    }
}
