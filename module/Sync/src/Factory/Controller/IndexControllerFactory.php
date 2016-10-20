<?php

namespace Sync\Factory\Controller;

use Sync\Controller\IndexController;
use Sync\Service\IndexService;
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
