<?php
namespace Console\Service;

use Common\Constant\EEN;
use Console\Service\Event\EventService;
use Console\Service\Opportunity\OpportunityService;

class ImportService
{
    /** @var OpportunityService */
    private $opportunityService;
    /** @var EventService */
    private $eventService;

    /**
     * ImportService constructor.
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
     * @param string $month
     */
    public function import($index, $month)
    {
        switch ($index) {
            case EEN::ES_INDEX_OPPORTUNITY:
                $this->opportunityService->import($month);
                break;
            case EEN::ES_INDEX_EVENT:
                $this->eventService->import();
                break;
        }
    }
}
