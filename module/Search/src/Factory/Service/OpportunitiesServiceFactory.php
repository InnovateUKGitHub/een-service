<?php

namespace Search\Factory\Service;

use Search\Service\OpportunitiesService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

final class OpportunitiesServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return OpportunitiesService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $query = $serviceManager->get(QueryService::class);

        return new OpportunitiesService($query);
    }
}