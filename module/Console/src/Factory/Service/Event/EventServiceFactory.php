<?php

namespace Console\Factory\Service\Event;

use Console\Service\Event\EventBrite;
use Console\Service\Event\EventMerlin;
use Console\Service\Event\EventService;
use Console\Service\Event\MerlinIngest;
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
        $merlinData = $serviceManager->get(EventMerlin::class);
        $merlinIngest = $serviceManager->get(MerlinIngest::class);
        $eventBrite = $serviceManager->get(EventBrite::class);

        return new EventService($indexService, $merlinData, $merlinIngest, $eventBrite);
    }
}
