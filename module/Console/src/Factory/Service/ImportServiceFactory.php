<?php

namespace Console\Factory\Service;

use Console\Service\Event\EventService;
use Console\Service\ImportService;
use Console\Service\Opportunity\OpportunityService;
use Zend\ServiceManager\ServiceManager;

final class ImportServiceFactory
{
    const CONFIG_SERVICE = 'config';
    const CONFIG_MERLIN = 'merlin';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return ImportService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $opportunityService = $serviceManager->get(OpportunityService::class);
        $eventService = $serviceManager->get(EventService::class);

        return new ImportService($opportunityService, $eventService);
    }
}
