<?php

namespace Console\Factory\Controller;

use Console\Controller\IndexController;
use Console\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

final class IndexControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return IndexController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(IndexService::class);

        return new IndexController($service);
    }
}
