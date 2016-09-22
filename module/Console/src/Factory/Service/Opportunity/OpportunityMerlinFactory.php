<?php

namespace Console\Factory\Service\Opportunity;

use Common\Factory\HttpServiceFactory;
use Console\Service\Opportunity\OpportunityMerlin;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class OpportunityMerlinFactory
{
    const CONFIG = 'config';
    const MERLIN = 'merlin';
    const SERVER = 'server';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunityMerlin
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(self::CONFIG);
        $this->checkRequiredConfig($config);

        $client = (new HttpServiceFactory())->__invoke($serviceManager);
        $client->setServer($config[self::MERLIN][self::SERVER]);
        $client->setHeaders([
            'Content-type' => 'application/xml',
            'Accept'       => 'application/xml',
        ]);

        $logger = $serviceManager->get(Logger::class);

        return new OpportunityMerlin($client, $logger, $config[self::MERLIN]);
    }

    private function checkRequiredConfig($config)
    {
        if (array_key_exists(self::MERLIN, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the merlin information');
        }
        if (array_key_exists(self::SERVER, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }

        if (array_key_exists(OpportunityMerlin::PATH_GET_PROFILE, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_profile');
        }
        if (array_key_exists(OpportunityMerlin::USERNAME, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(OpportunityMerlin::PASSWORD, $config[self::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }
    }
}
