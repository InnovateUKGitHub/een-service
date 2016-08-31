<?php

namespace Console\Factory\Controller;

use Console\Controller\ImportController;
use Console\Service\ImportService;
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
        /** @var ImportService $generateService */
        $importService = $serviceManager->get(ImportService::class);

        return new ImportController($importService);
    }
}
