<?php

namespace ConsoleTest\Service\Import;

use Console\Service\Import\EventService;
use Console\Service\Import\ImportService;
use Console\Service\Import\OpportunityService;

/**
 * @covers Console\Service\Import\ImportService
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
            ->with(self::MONTH, 's');
        $this->eventServiceMock->expects(self::never())
            ->method('import');

        $this->service->import(ES_INDEX_OPPORTUNITY, self::MONTH, 's');
    }

    public function testImportEvent()
    {
        $this->eventServiceMock->expects(self::once())
            ->method('import');
        $this->opportunityServiceMock->expects(self::never())
            ->method('import');

        $this->service->import(ES_INDEX_EVENT, self::MONTH, 's');
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
