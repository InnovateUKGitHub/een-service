<?php

namespace Contact\Factory\Controller;

use Contact\Controller\LeadController;
use Contact\Service\LeadService;
use Zend\ServiceManager\ServiceManager;

final class LeadControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return LeadController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(LeadService::class);

        return new LeadController($service);
    }
}
