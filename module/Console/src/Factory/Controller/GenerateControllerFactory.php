<?php

namespace Console\Factory\Controller;

use Console\Controller\GenerateController;
use Console\Service\DeleteService;
use Console\Service\GenerateService;
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
        /** @var GenerateService $generateService */
        $generateService = $serviceManager->get(GenerateService::class);
        /** @var DeleteService $deleteService */
        $deleteService = $serviceManager->get(DeleteService::class);

        return new GenerateController($generateService, $deleteService);
    }
}
