<?php

namespace Console\Factory\Service;

use Console\Service\DeleteService;
use Elasticsearch\ClientBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class DeleteServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return DeleteService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $elasticSearch = ClientBuilder::create()->build();

        return new DeleteService($elasticSearch);
    }
}
