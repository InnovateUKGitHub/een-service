<?php

namespace Console\Factory\Service\Merlin;

use Console\Factory\Service\HttpServiceFactory;
use Console\Service\Merlin\OpportunityMerlin;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class OpportunityMerlinFactory
{
    const CONFIG_SERVICE = 'config';
    const CONFIG_MERLIN = 'merlin';
    const SERVER = 'server';
    const PORT = 'port';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunityMerlin
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $client = (new HttpServiceFactory())->__invoke($serviceManager);
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

        if (array_key_exists(OpportunityMerlin::PATH_GET_PROFILE, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_profile');
        }
        if (array_key_exists(OpportunityMerlin::USERNAME, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(OpportunityMerlin::PASSWORD, $config[self::CONFIG_MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }

        $client->setServer($config[self::CONFIG_MERLIN][self::SERVER]);

        $client->setHeaders([
            'Content-type' => 'application/xml',
            'Accept' => 'application/xml',
        ]);

        $logger = $serviceManager->get(Logger::class);

        return new OpportunityMerlin($client, $logger, $config[self::CONFIG_MERLIN]);
    }
}
