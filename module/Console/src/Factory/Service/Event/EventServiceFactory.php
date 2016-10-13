<?php

namespace Console\Factory\Service\Event;

use Console\Service\Event\EventBrite;
use Console\Service\Event\EventService;
use Console\Service\Event\Merlin;
use Console\Service\Event\SalesForce;
use Console\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

final class EventServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $merlin = $serviceManager->get(Merlin::class);
        $eventBrite = $serviceManager->get(EventBrite::class);
        $salesForce = $serviceManager->get(SalesForce::class);

        return new EventService($indexService, $merlin, $eventBrite, $salesForce);
    }
}
