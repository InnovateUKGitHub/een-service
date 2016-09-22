<?php

namespace Console\Factory\Service\Event;

use Common\Factory\HttpServiceFactory;
use Console\Service\Event\EventMerlin;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class EventMerlinFactory
{
    const CONFIG = 'config';
    const MERLIN = 'merlin';
    const SERVER = 'server';
    const PORT = 'port';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventMerlin
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $client = (new HttpServiceFactory())->__invoke($serviceManager);
        $config = $serviceManager->get(self::CONFIG);

        // Test if the require keys are present in the configuration
        if (array_key_exists(self::MERLIN, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the merlin information');
        }
        if (array_key_exists(self::SERVER, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }
        if (array_key_exists(self::PORT, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the port');
        }

        if (array_key_exists(EventMerlin::PATH_GET_EVENT, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_event');
        }
        if (array_key_exists(EventMerlin::USERNAME, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(EventMerlin::PASSWORD, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }

        $client->setServer($config[self::MERLIN][self::SERVER]);

        $client->setHeaders([
            'Content-type' => 'application/xml',
            'Accept'       => 'application/xml',
        ]);

        $logger = $serviceManager->get(Logger::class);

        return new EventMerlin($client, $logger, $config[self::MERLIN]);
    }
}
