<?php

namespace Mail\Factory\Controller;

use Mail\Controller\TemplateController;
use Mail\Service\TemplateService;
use Zend\ServiceManager\ServiceManager;

final class TemplateControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return TemplateController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(TemplateService::class);

        return new TemplateController($service);
    }
}
