<?php

namespace Sync\Factory\Service\Event;

use Sync\Service\Event\EventBrite;
use Sync\Service\Event\EventService;
use Sync\Service\Event\Merlin;
use Sync\Service\Event\SalesForce;
use Sync\Service\IndexService;
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
