<?php

namespace Contact\Factory\Service;

use Contact\Service\ContactService;
use Contact\Service\SalesForceService;
use Zend\ServiceManager\ServiceManager;

final class ContactServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return ContactService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $salesForce = $serviceManager->get(SalesForceService::class);

        return new ContactService($salesForce);
    }
}