<?php

namespace Search\Factory\Service;

use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

final class ElasticSearchServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return ElasticSearchService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /** @var QueryService $query */
        $query = $serviceManager->get(QueryService::class);

        return new ElasticSearchService($query);
    }
}