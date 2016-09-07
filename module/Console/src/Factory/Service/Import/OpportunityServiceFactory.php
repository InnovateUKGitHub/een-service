<?php

namespace Console\Factory\Service\Import;

use Console\Service\Import\OpportunityService;
use Console\Service\IndexService;
use Console\Service\Merlin\OpportunityMerlin;
use Console\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

final class OpportunityServiceFactory
{
    const CONFIG = 'config';
    const MERLIN_DATA_STRUCTURE = 'merlin-profile-structure';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunityService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $merlinData = $serviceManager->get(OpportunityMerlin::class);
        $merlinValidator = $serviceManager->get(MerlinValidator::class);

        $config = $serviceManager->get(self::CONFIG);

        if (array_key_exists(self::MERLIN_DATA_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }

        return new OpportunityService($indexService, $merlinData, $merlinValidator, $config[self::MERLIN_DATA_STRUCTURE]);
    }
}
