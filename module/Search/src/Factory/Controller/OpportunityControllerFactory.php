<?php

namespace Search\Factory\Controller;

use Search\Controller\OpportunitiesController;
use Search\Service\ElasticSearchService;
use Zend\Mvc\Controller\ControllerManager;

final class OpportunitiesControllerFactory
{
    /**
     * @param ControllerManager $controllers
     *
     * @return OpportunitiesController
     */
    public function __invoke(ControllerManager $controllers)
    {
        $serviceLocator = $controllers->getServiceLocator();
        /** @var ElasticSearchService $service */
        $service = $serviceLocator->get(ElasticSearchService::class);

        return new OpportunitiesController($service);
    }
}
