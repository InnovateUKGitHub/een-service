<?php

namespace Search\Factory\Controller;

use Search\Controller\CountryController;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

final class CountryControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return CountryController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(QueryService::class);

        return new CountryController($service);
    }
}
