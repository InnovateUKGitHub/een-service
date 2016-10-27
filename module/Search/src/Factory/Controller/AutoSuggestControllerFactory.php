<?php

namespace Search\Factory\Controller;

use Search\Controller\AutoSuggestController;
use Search\Service\QueryService;
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
        $service = $serviceManager->get(QueryService::class);

        return new AutoSuggestController($service);
    }
}
