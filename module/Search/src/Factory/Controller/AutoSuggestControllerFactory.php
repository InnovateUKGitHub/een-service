<?php

namespace Search\Factory\Controller;

use Search\Controller\AutoSuggestController;
use Search\Service\ElasticSearchService;
use Zend\ServiceManager\ServiceManager;

final class AutoSuggestControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return AutoSuggestController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(ElasticSearchService::class);

        return new AutoSuggestController($service);
    }
}
