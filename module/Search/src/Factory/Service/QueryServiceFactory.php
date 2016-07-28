<?php

namespace Search\Factory\Service;

use Elasticsearch\ClientBuilder;
use Search\Service\QueryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QueryServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sm
     *
     * @return QueryService
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $elasticSearch = ClientBuilder::create()->build();

        return new QueryService($elasticSearch);
    }
}