<?php

namespace Sync\Factory\Service;

use Faker\Factory;
use Sync\Service\GenerateService;
use Sync\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

final class GenerateServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return GenerateService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(IndexService::class);
        $faker = Factory::create();

        return new GenerateService($service, $faker);
    }
}
