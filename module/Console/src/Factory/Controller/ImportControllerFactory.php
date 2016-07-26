<?php

namespace Console\Factory\Controller;

use Console\Controller\ImportController;
use Console\Service\ImportService;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class ImportControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ImportController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocator $sl */
        $sl = $serviceLocator->getServiceLocator();
        /** @var ImportService $generateService */
        $importService = $sl->get(ImportService::class);

        return new ImportController($importService);
    }
}
