<?php

namespace SyncTest\Factory\Service;

use Sync\Factory\Service\DeleteServiceFactory;
use Sync\Service\DeleteService;
use Sync\Service\Event\EventService;
use Sync\Service\Opportunity\OpportunityService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\DeleteServiceFactory
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
