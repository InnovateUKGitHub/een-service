<?php

namespace SyncTest\Factory\Service\Import;

use Sync\Factory\Service\ImportServiceFactory;
use Sync\Service\Event\EventService;
use Sync\Service\ImportService;
use Sync\Service\Opportunity\OpportunityService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\ImportServiceFactory
 */
class ImportServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(OpportunityService::class)
            ->willReturn($this->createMock(OpportunityService::class));
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(EventService::class)
            ->willReturn($this->createMock(EventService::class));

        self::assertInstanceOf(
            ImportService::class,
            (new ImportServiceFactory())->__invoke($serviceManager)
        );
    }
}
