<?php

namespace Sync\Factory\Service\Event;

use Common\Service\SalesForceService;
use Sync\Service\Event\SalesForce;
use Sync\Service\IndexService;
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
