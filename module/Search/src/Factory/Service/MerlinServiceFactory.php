<?php

namespace Search\Factory\Service;

use Console\Service\ImportService;
use Search\Service\MerlinService;
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