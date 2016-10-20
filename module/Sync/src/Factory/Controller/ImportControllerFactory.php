<?php

namespace Sync\Factory\Controller;

use Sync\Controller\ImportController;
use Sync\Service\DeleteService;
use Sync\Service\ImportService;
use Zend\ServiceManager\ServiceManager;

final class ImportControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return ImportController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $importService = $serviceManager->get(ImportService::class);
        $deleteService = $serviceManager->get(DeleteService::class);

        return new ImportController($importService, $deleteService);
    }
}
