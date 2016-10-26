<?php

namespace SyncTest\Service\Import;

use Common\Constant\EEN;
use Sync\Service\DeleteService;
use Sync\Service\Event\EventService;
use Sync\Service\Opportunity\OpportunityService;

/**
 * @covers \Sync\Service\DeleteService
 */
class DeleteServiceTest extends \PHPUnit_Framework_TestCase
{
    const MONTH = 1;

    /** @var \PHPUnit_Framework_MockObject_MockObject|OpportunityService $opportunityServiceMock */
    private $opportunityServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|EventService $eventServiceMock */
    private $eventServiceMock;
    /** @var DeleteService $service */
    private $service;

    public function testDeleteOutOfDateOpportunity()
    {
        $this->opportunityServiceMock
            ->expects(self::once())
            ->method('delete');
        $this->eventServiceMock
            ->expects(self::never())
            ->method('delete');

        $this->service->deleteOutOfDate(EEN::ES_INDEX_OPPORTUNITY);
    }

    public function testDeleteOutOfDateEvent()
    {
        $this->opportunityServiceMock
            ->expects(self::never())
            ->method('delete');
        $this->eventServiceMock
            ->expects(self::once())
            ->method('delete');

        $this->service->deleteOutOfDate(EEN::ES_INDEX_EVENT);
    }

    protected function Setup()
    {
        $this->opportunityServiceMock = $this->createMock(OpportunityService::class);
        $this->eventServiceMock = $this->createMock(EventService::class);

        $this->service = new DeleteService(
            $this->opportunityServiceMock,
            $this->eventServiceMock
        );
    }
}
