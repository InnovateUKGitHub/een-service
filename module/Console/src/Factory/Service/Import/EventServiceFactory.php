<?php

namespace Console\Factory\Service\Import;

use Console\Service\Import\EventService;
use Console\Service\IndexService;
use Console\Service\Merlin\EventMerlin;
use Console\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

final class EventServiceFactory
{
    const CONFIG = 'config';
    const MERLIN_DATA_STRUCTURE = 'merlin-event-structure';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return EventService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $eventMerlin = $serviceManager->get(EventMerlin::class);
        $merlinValidator = $serviceManager->get(MerlinValidator::class);

        $config = $serviceManager->get(self::CONFIG);

        if (array_key_exists(self::MERLIN_DATA_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }

        return new EventService($indexService, $eventMerlin, $merlinValidator, $config[self::MERLIN_DATA_STRUCTURE]);
    }
}
