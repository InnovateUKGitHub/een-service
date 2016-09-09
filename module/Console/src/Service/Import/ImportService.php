<?php
namespace Console\Service\Import;

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
     * @param string $type
     */
    public function import($index, $month, $type)
    {
        switch ($index) {
            case ES_INDEX_OPPORTUNITY:
                $this->opportunityService->import($month, $type);
                break;
            case ES_INDEX_EVENT:
                $this->eventService->import();
                break;
        }
    }
}
