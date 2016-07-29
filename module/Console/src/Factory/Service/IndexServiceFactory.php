<?php

namespace Console\Factory\Service;

use Console\Service\IndexService;
use Elasticsearch\ClientBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class IndexServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return IndexService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $elasticSearch = ClientBuilder::create()->build();

        return new IndexService($elasticSearch);
    }
}
