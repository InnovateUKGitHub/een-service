<?php

namespace Search\Factory\Controller;

use Search\Controller\EventsController;
use Search\Service\ElasticSearchService;
use Zend\ServiceManager\ServiceManager;

final class EventsControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventsController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /** @var ElasticSearchService $service */
        $service = $serviceManager->get(ElasticSearchService::class);

        return new EventsController($service);
    }
}
