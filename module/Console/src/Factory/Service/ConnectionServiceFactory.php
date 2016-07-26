<?php

namespace Console\Factory\Service;

use Console\Service\ConnectionService;
use Console\Service\HttpService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Exception\InvalidArgumentException;

final class ConnectionServiceFactory implements FactoryInterface
{
    const CONFIG_SERVICE = 'config';
    const CONFIG_ELASTIC_SEARCH = 'elastic-search';

    /**
     * {@inheritDoc}
     *
     * @return ConnectionService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(self::CONFIG_SERVICE);
        if (array_key_exists(self::CONFIG_ELASTIC_SEARCH, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server information');
        }

        /** @var HttpService $httpService */
        $httpService = $serviceLocator->get(HttpService::class);

        return new ConnectionService($httpService, $config[self::CONFIG_ELASTIC_SEARCH]);
    }
}
