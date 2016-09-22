<?php

namespace ConsoleTest\Factory\Service\Import;

use Console\Factory\Service\ImportServiceFactory;
use Console\Service\Event\EventService;
use Console\Service\ImportService;
use Console\Service\Opportunity\OpportunityService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Console\Factory\Service\ImportServiceFactory
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
