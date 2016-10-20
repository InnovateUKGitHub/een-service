<?php

namespace Sync\Factory\Service;

use Common\Constant\EEN;
use Sync\Service\IndexService;
use Elasticsearch\ClientBuilder;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class IndexServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return IndexService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $elasticSearch = ClientBuilder::create()->build();

        $logger = $serviceManager->get(Logger::class);
        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        return new IndexService($elasticSearch, $logger, $config[EEN::ELASTIC_SEARCH_INDEXES]);
    }

    /**
     * @param array $config
     */
    private function checkRequiredConfig($config)
    {
        if (array_key_exists(EEN::ELASTIC_SEARCH_INDEXES, $config) === false) {
            throw new \InvalidArgumentException(
                'The config file is incorrect. Please specify the elastic-search indexes information'
            );
        }
    }
}
