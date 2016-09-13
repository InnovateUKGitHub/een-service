<?php

namespace Console\Factory\Service\Import;

use Console\Service\Import\Event\EventBrite;
use Console\Service\Import\Event\MerlinIngest;
use Console\Service\Import\EventService;
use Console\Service\IndexService;
use Console\Service\Merlin\EventMerlin;
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
