<?php

namespace Console\Service\Import;

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

    public function deleteOutOfDate($index, $since)
    {
        switch ($index) {
            case ES_INDEX_OPPORTUNITY:
                $this->opportunityService->delete($since, new \DateTime());
                break;
            case ES_INDEX_EVENT:
                $this->eventService->delete(new \DateTime());
                break;
        }
    }
}
