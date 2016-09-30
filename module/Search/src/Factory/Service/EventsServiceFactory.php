<?php

namespace Search\Factory\Service;

use Search\Service\EventsService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

final class EventsServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventsService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $query = $serviceManager->get(QueryService::class);

        return new EventsService($query);
    }
}