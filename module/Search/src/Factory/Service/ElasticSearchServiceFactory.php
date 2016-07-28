<?php

namespace Search\Factory\Service;

use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ElasticSearchServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sm
     *
     * @return ElasticSearchService
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        /** @var QueryService $query */
        $query = $sm->get(QueryService::class);

        return new ElasticSearchService($query);
    }
}