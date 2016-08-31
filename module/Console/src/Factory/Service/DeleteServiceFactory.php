<?php

namespace Console\Factory\Service;

use Console\Service\DeleteService;
use Elasticsearch\ClientBuilder;
use Zend\ServiceManager\ServiceManager;

final class DeleteServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return DeleteService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $elasticSearch = ClientBuilder::create()->build();

        return new DeleteService($elasticSearch);
    }
}
