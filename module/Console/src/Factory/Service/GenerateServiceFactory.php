<?php

namespace Console\Factory\Service;

use Console\Service\GenerateService;
use Console\Service\IndexService;
use Faker\Factory;
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
