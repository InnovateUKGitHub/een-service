<?php

namespace Contact\Factory\Controller;

use Contact\Controller\EventController;
use Contact\Service\EventService;
use Zend\ServiceManager\ServiceManager;

final class EventControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(EventService::class);

        return new EventController($service);
    }
}
