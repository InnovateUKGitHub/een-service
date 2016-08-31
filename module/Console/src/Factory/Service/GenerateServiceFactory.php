<?php

namespace Console\Factory\Service;

use Console\Service\GenerateService;
use Console\Service\IndexService;
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
        /** @var IndexService $service */
        $service = $serviceManager->get(IndexService::class);
        $faker = \Faker\Factory::create();

        return new GenerateService($service, $faker);
    }
}
