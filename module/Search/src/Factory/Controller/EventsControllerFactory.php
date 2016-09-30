<?php

namespace Search\Factory\Controller;

use Search\Controller\EventsController;
use Search\Service\EventsService;
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
        $service = $serviceManager->get(EventsService::class);

        return new EventsController($service);
    }
}
