<?php

namespace Console\Factory\Service;

use Console\Service\GenerateService;
use Console\Service\IndexService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class GenerateServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return GenerateService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var IndexService $service */
        $service = $serviceLocator->get(IndexService::class);
        $faker = \Faker\Factory::create();

        return new GenerateService($service, $faker);
    }
}
