<?php

namespace Sync\Factory\Controller;

use Sync\Controller\GenerateController;
use Sync\Service\GenerateService;
use Sync\Service\PurgeService;
use Zend\ServiceManager\ServiceManager;

final class GenerateControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return GenerateController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $generateService = $serviceManager->get(GenerateService::class);
        $deleteService = $serviceManager->get(PurgeService::class);

        return new GenerateController($generateService, $deleteService);
    }
}
