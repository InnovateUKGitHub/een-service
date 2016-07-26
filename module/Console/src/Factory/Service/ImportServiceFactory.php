<?php

namespace Console\Factory\Service;

use Console\Service\ImportService;
use Console\Service\HttpService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Exception\InvalidArgumentException;

final class ImportServiceFactory implements FactoryInterface
{
    const CONFIG_SERVICE = 'config';
    const CONFIG_MERLIN = 'merlin';

    /**
     * {@inheritDoc}
     *
     * @return ImportService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var HttpService $httpService */
        $httpService = $serviceLocator->get(HttpService::class);
        $config = $serviceLocator->get(self::CONFIG_SERVICE);
        if (array_key_exists(self::CONFIG_MERLIN, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the merlin information');
        }

        return new ImportService($httpService, $config[self::CONFIG_MERLIN]);
    }
}
