<?php

namespace Contact\Factory\Controller;

use Contact\Controller\EoiController;
use Contact\Service\EoiService;
use Zend\ServiceManager\ServiceManager;

final class EoiControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EoiController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(EoiService::class);

        return new EoiController($service);
    }
}
