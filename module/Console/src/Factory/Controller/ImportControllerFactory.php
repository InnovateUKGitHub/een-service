<?php

namespace Console\Factory\Controller;

use Console\Controller\ImportController;
use Console\Service\Import\DeleteService;
use Console\Service\Import\ImportService;
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
