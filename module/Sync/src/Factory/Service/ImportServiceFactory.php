<?php

namespace Sync\Factory\Service;

use Sync\Service\Event\EventService;
use Sync\Service\ImportService;
use Sync\Service\Opportunity\OpportunityService;
use Zend\ServiceManager\ServiceManager;

final class ImportServiceFactory
{
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
