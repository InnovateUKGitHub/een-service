<?php

namespace Console\Factory\Service\Opportunity;

use Common\Constant\EEN;
use Common\Factory\HttpServiceFactory;
use Console\Service\Opportunity\OpportunityMerlin;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class OpportunityMerlinFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunityMerlin
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(EEN::CONFIG);
        $this->checkRequiredConfig($config);

        $config = $config[EEN::MERLIN];

        $client = (new HttpServiceFactory())->__invoke($serviceManager);
        $client->setServer($config[EEN::SERVER]);
        $client->setHeaders([
            'Content-type' => 'application/xml',
            'Accept'       => 'application/xml',
        ]);

        $logger = $serviceManager->get(Logger::class);

        return new OpportunityMerlin(
            $client,
            $logger,
            $config[EEN::USERNAME],
            $config[EEN::PASSWORD],
            $config[EEN::PATH_PROFILE]
        );
    }

    /**
     * @param array $config
     */
    private function checkRequiredConfig($config)
    {
        if (array_key_exists(EEN::MERLIN, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the merlin information');
        }
        if (array_key_exists(EEN::SERVER, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }

        if (array_key_exists(EEN::PATH_PROFILE, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_profile');
        }
        if (array_key_exists(EEN::USERNAME, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(EEN::PASSWORD, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }
    }
}
