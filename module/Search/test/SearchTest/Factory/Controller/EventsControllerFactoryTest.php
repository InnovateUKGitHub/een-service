<?php

namespace SearchTest\Factory\Controller;

use Search\Controller\EventsController;
use Search\Factory\Controller\EventsControllerFactory;
use Search\Service\EventsService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Controller\EventsControllerFactory
 */
class EventsControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(EventsService::class)
            ->willReturn($this->createMock(EventsService::class));

        self::assertInstanceOf(
            EventsController::class,
            (new EventsControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}