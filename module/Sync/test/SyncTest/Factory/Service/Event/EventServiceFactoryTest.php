<?php

namespace SyncTest\Factory\Service\Event;

use Sync\Factory\Service\Event\EventServiceFactory;
use Sync\Service\Event\EventBrite;
use Sync\Service\Event\EventService;
use Sync\Service\Event\Merlin;
use Sync\Service\Event\SalesForce;
use Sync\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\Event\EventServiceFactory
 */
class EventServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(Merlin::class)
            ->willReturn($this->createMock(Merlin::class));
        $serviceManager->expects(self::at(2))
            ->method('get')
            ->with(EventBrite::class)
            ->willReturn($this->createMock(EventBrite::class));
        $serviceManager->expects(self::at(3))
            ->method('get')
            ->with(SalesForce::class)
            ->willReturn($this->createMock(SalesForce::class));

        self::assertInstanceOf(
            EventService::class,
            (new EventServiceFactory())->__invoke($serviceManager)
        );
    }
}
