<?php

namespace Search\V1\Merlin\Factory;

use Console\Service\ImportService;
use Search\V1\Merlin\Service\MerlinService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MerlinServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sm
     *
     * @return MerlinService
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        /** @var ImportService $service */
        $service = $sm->get(ImportService::class);

        return new MerlinService($service);
    }
}