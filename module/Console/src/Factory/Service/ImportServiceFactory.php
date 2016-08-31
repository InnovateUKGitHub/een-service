<?php

namespace Console\Factory\Service;

use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class ImportServiceFactory
{
    const CONFIG_SERVICE = 'config';

    const CONFIG_MERLIN = 'merlin';

    const SERVER = 'server';

    const PORT = 'port';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return ImportService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $httpService = $serviceManager->get(HttpService::class);
        $indexService = $serviceManager->get(IndexService::class);

        $config = $serviceManager->get(self::CONFIG_SERVICE);

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

        $merlinValidator = $serviceManager->get(MerlinValidator::class);

        $logger = $serviceManager->get(Logger::class);

        return new ImportService($httpService, $indexService, $merlinValidator, $logger, $config[self::CONFIG_MERLIN]);
    }
}
