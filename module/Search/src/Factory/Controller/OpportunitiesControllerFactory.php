<?php

namespace Search\Factory\Controller;

use Search\Controller\OpportunitiesController;
use Search\Service\ElasticSearchService;
use Zend\ServiceManager\ServiceManager;

final class OpportunitiesControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunitiesController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /** @var ElasticSearchService $service */
        $service = $serviceManager->get(ElasticSearchService::class);

        return new OpportunitiesController($service);
    }
}
