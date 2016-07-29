<?php

namespace Console\Factory\Service;

use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class ImportServiceFactory implements FactoryInterface
{
    const CONFIG_SERVICE = 'config';
    const CONFIG_MERLIN = 'merlin';

    const SERVER = 'server';
    const PORT = 'port';

    /**
     * {@inheritDoc}
     *
     * @return ImportService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var HttpService $httpService */
        $httpService = $serviceLocator->get(HttpService::class);
        /** @var IndexService $indexService */
        $indexService = $serviceLocator->get(IndexService::class);

        $config = $serviceLocator->get(self::CONFIG_SERVICE);

        // Test if the require keys are present in the configuration
        if (array_key_exists(self::CONFIG_MERLIN, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the merlin information');
        }
        if (array_key_exists(self::SERVER, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }
        if (array_key_exists(self::PORT, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the port');
        }

        if (array_key_exists(ImportService::USERNAME, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(ImportService::PASSWORD, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }
        if (array_key_exists(ImportService::PATH_GET_PROFILE, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_profile');
        }

        $httpService->setServer($config[self::CONFIG_MERLIN][self::SERVER]);
        $httpService->setPort($config[self::CONFIG_MERLIN][self::PORT]);
        $httpService->setHeaders([
            'Content-type' => 'application/xml',
        ]);

        return new ImportService($httpService, $indexService, $config[self::CONFIG_MERLIN]);
    }
}
