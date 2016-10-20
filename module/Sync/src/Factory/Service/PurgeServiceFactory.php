<?php

namespace Sync\Factory\Service;

use Sync\Service\PurgeService;
use Elasticsearch\ClientBuilder;
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
        $elasticSearch = ClientBuilder::create()->build();

        return new PurgeService($elasticSearch);
    }
}
