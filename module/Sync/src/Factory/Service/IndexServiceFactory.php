<?php

namespace Sync\Factory\Service;

use Common\Constant\EEN;
use Elasticsearch\ClientBuilder;
use Sync\Service\IndexService;
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

        $logger = $serviceManager->get(Logger::class);
        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        $elasticSearch = ClientBuilder::create()
            ->setHosts($config[EEN::ELASTIC_SEARCH_HOST])
            ->build();


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
