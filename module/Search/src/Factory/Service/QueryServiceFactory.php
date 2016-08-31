<?php

namespace Search\Factory\Service;

use Elasticsearch\ClientBuilder;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

final class QueryServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return QueryService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $elasticSearch = ClientBuilder::create()->build();

        return new QueryService($elasticSearch);
    }
}