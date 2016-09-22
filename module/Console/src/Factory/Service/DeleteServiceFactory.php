<?php

namespace Console\Factory\Service;

use Console\Service\DeleteService;
use Console\Service\Event\EventService;
use Console\Service\Opportunity\OpportunityService;
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
