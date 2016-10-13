<?php

namespace Contact\Factory\Service;

use Common\Service\SalesForceService;
use Contact\Service\LeadService;
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