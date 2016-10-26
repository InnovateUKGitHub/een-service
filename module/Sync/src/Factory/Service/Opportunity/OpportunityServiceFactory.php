<?php

namespace Sync\Factory\Service\Opportunity;

use Common\Constant\EEN;
use Sync\Service\IndexService;
use Sync\Service\Opportunity\OpportunityMerlin;
use Sync\Service\Opportunity\OpportunityService;
use Sync\Validator\MerlinValidator;
use Zend\Escaper\Escaper;
use Zend\ServiceManager\ServiceManager;

final class OpportunityServiceFactory
{
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

        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        return new OpportunityService(
            $indexService,
            $merlinData,
            $merlinValidator,
            new \HTMLPurifier(),
            new Escaper(),
            $config[EEN::MERLIN_PROFILE_STRUCTURE]
        );
    }

    /**
     * @param array $config
     */
    private function checkRequiredConfig($config)
    {
        if (array_key_exists(EEN::MERLIN_PROFILE_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }
    }
}
