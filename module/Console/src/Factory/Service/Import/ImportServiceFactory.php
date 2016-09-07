<?php

namespace Console\Factory\Service\Import;

use Console\Service\Import\EventService;
use Console\Service\Import\ImportService;
use Console\Service\Import\OpportunityService;
use Zend\ServiceManager\ServiceManager;

final class ImportServiceFactory
{
    const CONFIG_SERVICE = 'config';

    const CONFIG_MERLIN = 'merlin';

    const SERVER = 'server';

    const PORT = 'port';

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
