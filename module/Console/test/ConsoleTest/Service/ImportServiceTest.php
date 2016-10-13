<?php

namespace ConsoleTest\Service\Import;

use Common\Constant\EEN;
use Console\Service\Event\EventService;
use Console\Service\ImportService;
use Console\Service\Opportunity\OpportunityService;

/**
 * @covers \Console\Service\ImportService
 */
class ImportServiceTest extends \PHPUnit_Framework_TestCase
{
    const MONTH = 1;

    /** @var \PHPUnit_Framework_MockObject_MockObject|OpportunityService $opportunityServiceMock */
    private $opportunityServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|EventService $eventServiceMock */
    private $eventServiceMock;
    /** @var ImportService $service */
    private $service;

    public function testImportOpportunity()
    {
        $this->opportunityServiceMock->expects(self::once())
            ->method('import')
            ->with(self::MONTH);
        $this->eventServiceMock->expects(self::never())
            ->method('import');

        $this->service->import(EEN::ES_INDEX_OPPORTUNITY, self::MONTH);
    }

    public function testImportEvent()
    {
        $this->eventServiceMock->expects(self::once())
            ->method('import');
        $this->opportunityServiceMock->expects(self::never())
            ->method('import');

        $this->service->import(EEN::ES_INDEX_EVENT, self::MONTH);
    }

    protected function Setup()
    {
        $this->opportunityServiceMock = $this->createMock(OpportunityService::class);
        $this->eventServiceMock = $this->createMock(EventService::class);

        $this->service = new ImportService(
            $this->opportunityServiceMock,
            $this->eventServiceMock
        );
    }
}
