<?php

namespace Search\Factory\Controller;

use Search\Controller\CountryController;
use Search\Service\ElasticSearchService;
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
        $service = $serviceManager->get(ElasticSearchService::class);

        return new CountryController($service);
    }
}
