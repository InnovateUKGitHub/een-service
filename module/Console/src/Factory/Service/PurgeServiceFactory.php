<?php

namespace Console\Factory\Service;

use Console\Service\PurgeService;
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
