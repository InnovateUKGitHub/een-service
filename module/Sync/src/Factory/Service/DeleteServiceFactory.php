<?php

namespace Sync\Factory\Service;

use Sync\Service\DeleteService;
use Sync\Service\Event\EventService;
use Sync\Service\Opportunity\OpportunityService;
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
