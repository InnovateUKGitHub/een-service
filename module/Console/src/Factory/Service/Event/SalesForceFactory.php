<?php

namespace Console\Factory\Service\Event;

use Common\Service\SalesForceService;
use Console\Service\Event\SalesForce;
use Console\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

final class SalesForceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return SalesForce
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $salesForceService = $serviceManager->get(SalesForceService::class);

        return new SalesForce($indexService, $salesForceService);
    }
}
