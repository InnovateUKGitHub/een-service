<?php

namespace Console\Factory\Service;

use Console\Service\IndexService;
use Elasticsearch\ClientBuilder;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class IndexServiceFactory
{
    const CONFIG = 'config';
    const ELASTIC_SEARCH = 'elastic-search-indexes';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return IndexService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $elasticSearch = ClientBuilder::create()->build();

        /** @var Logger $logger */
        $logger = $serviceManager->get(Logger::class);

        $config = $serviceManager->get(self::CONFIG);

        if (array_key_exists(self::ELASTIC_SEARCH, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the elastic-search information');
        }

        return new IndexService($elasticSearch, $logger, $config[self::ELASTIC_SEARCH]);
    }
}
