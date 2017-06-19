<?php

namespace Sync\Factory\Service;

use Common\Constant\EEN;
use Elasticsearch\ClientBuilder;
use Sync\Service\PurgeService;
use Zend\ServiceManager\ServiceManager;

final class PurgeServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return PurgeService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(EEN::CONFIG);

        $elasticSearch = ClientBuilder::create()
            ->setHosts($config[EEN::ELASTIC_SEARCH_HOST])
            ->build();

        return new PurgeService($elasticSearch);
    }
}
