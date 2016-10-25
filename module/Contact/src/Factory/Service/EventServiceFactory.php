<?php

namespace Contact\Factory\Service;

use Common\Service\SalesForceService;
use Contact\Service\EventService;
use Zend\ServiceManager\ServiceManager;

final class EventServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $salesForce = $serviceManager->get(SalesForceService::class);

        return new EventService($salesForce);
    }
}