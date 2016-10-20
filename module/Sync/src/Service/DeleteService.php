<?php

namespace Sync\Service;

use Common\Constant\EEN;
use Sync\Service\Event\EventService;
use Sync\Service\Opportunity\OpportunityService;

class DeleteService
{
    /** @var OpportunityService */
    private $opportunityService;
    /** @var EventService */
    private $eventService;

    /**
     * DeleteService constructor.
     *
     * @param OpportunityService $opportunityService
     * @param EventService       $eventService
     */
    public function __construct(OpportunityService $opportunityService, EventService $eventService)
    {
        $this->opportunityService = $opportunityService;
        $this->eventService = $eventService;
    }

    /**
     * @param string $index
     */
    public function deleteOutOfDate($index)
    {
        switch ($index) {
            case EEN::ES_INDEX_OPPORTUNITY:
                $this->opportunityService->delete(new \DateTime());
                break;
            case EEN::ES_INDEX_EVENT:
                $this->eventService->delete(new \DateTime());
                break;
        }
    }
}
