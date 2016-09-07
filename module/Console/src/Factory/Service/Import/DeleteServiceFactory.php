<?php

namespace Console\Factory\Service\Import;

use Console\Service\Import\DeleteService;
use Console\Service\Import\EventService;
use Console\Service\Import\OpportunityService;
use Zend\ServiceManager\ServiceManager;

final class DeleteServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return DeleteService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $opportunityService = $serviceManager->get(OpportunityService::class);
        $eventService = $serviceManager->get(EventService::class);

        return new DeleteService($opportunityService, $eventService);
    }
}
