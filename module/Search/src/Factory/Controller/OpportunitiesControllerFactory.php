<?php

namespace Search\Factory\Controller;

use Search\Controller\OpportunitiesController;
use Search\Service\OpportunitiesService;
use Zend\ServiceManager\ServiceManager;

final class OpportunitiesControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunitiesController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(OpportunitiesService::class);

        return new OpportunitiesController($service);
    }
}
