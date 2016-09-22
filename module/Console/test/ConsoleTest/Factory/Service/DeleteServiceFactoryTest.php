<?php

namespace ConsoleTest\Factory\Service\Import;

use Console\Factory\Service\DeleteServiceFactory;
use Console\Service\DeleteService;
use Console\Service\Event\EventService;
use Console\Service\Opportunity\OpportunityService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Console\Factory\Service\DeleteServiceFactory
 */
class DeleteServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(OpportunityService::class)
            ->willReturn($this->createMock(OpportunityService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(EventService::class)
            ->willReturn($this->createMock(EventService::class));

        self::assertInstanceOf(
            DeleteService::class,
            (new DeleteServiceFactory())->__invoke($serviceManager)
        );
    }
}
