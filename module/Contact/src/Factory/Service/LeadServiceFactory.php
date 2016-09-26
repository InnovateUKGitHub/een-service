<?php

namespace Contact\Factory\Service;

use Contact\Service\LeadService;
use Contact\Service\SalesForceService;
use Zend\ServiceManager\ServiceManager;

final class LeadServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return LeadService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $salesForce = $serviceManager->get(SalesForceService::class);

        return new LeadService($salesForce);
    }
}