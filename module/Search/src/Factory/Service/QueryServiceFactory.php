<?php

namespace Search\Factory\Service;

use Common\Constant\EEN;
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
        $config = $serviceManager->get(EEN::CONFIG);

        $elasticSearch = ClientBuilder::create()
            ->setHosts($config[EEN::ELASTIC_SEARCH_HOST])
            ->build();

        return new QueryService($elasticSearch);
    }
}