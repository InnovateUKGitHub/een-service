<?php

namespace ContactTest\Factory\Controller;

use Contact\Controller\EoiController;
use Contact\Controller\EventController;
use Contact\Factory\Controller\EoiControllerFactory;
use Contact\Factory\Controller\EventControllerFactory;
use Contact\Service\EoiService;
use Contact\Service\EventService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Controller\EventControllerFactory
 */
class EventControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(EventService::class)
            ->willReturn($this->createMock(EventService::class));

        self::assertInstanceOf(
            EventController::class,
            (new EventControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}