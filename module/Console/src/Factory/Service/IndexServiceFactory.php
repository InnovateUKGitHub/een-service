<?php

namespace Console\Factory\Service;

use Console\Service\IndexService;
use Elasticsearch\ClientBuilder;
use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class IndexServiceFactory implements FactoryInterface
{
    const CONFIG = 'config';
    const ELASTIC_SEARCH = 'elastic-search-indexes';

    /**
     * {@inheritDoc}
     *
     * @return IndexService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $elasticSearch = ClientBuilder::create()->build();

        /** @var Logger $logger */
        $logger = $serviceLocator->get(Logger::class);

        $config = $serviceLocator->get(self::CONFIG);

        if (array_key_exists(self::ELASTIC_SEARCH, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the elastic-search information');
        }

        return new IndexService($elasticSearch, $logger, $config[self::ELASTIC_SEARCH]);
    }
}
