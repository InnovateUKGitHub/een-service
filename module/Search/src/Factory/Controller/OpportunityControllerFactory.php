<?php

namespace Search\Factory\Controller;

use Search\Controller\OpportunitiesController;
use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
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
        /** @var MerlinService $merlin */
        $merlin = $serviceLocator->get(MerlinService::class);

        return new OpportunitiesController($service, $merlin);
    }
}
