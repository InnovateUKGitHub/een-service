<?php

namespace ConsoleTest\Factory\Controller;

use Console\Controller\GenerateController;
use Console\Factory\Controller\GenerateControllerFactory;
use Console\Service\DeleteService;
use Console\Service\GenerateService;
use Zend\Mvc\Controller\ControllerManager;

/**
 * @covers Console\Factory\Controller\GenerateControllerFactory
 */
class GenerateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var $serviceLocator ControllerManager|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = $this->createMock(ControllerManager::class);

        $serviceLocator
            ->expects(self::at(0))
            ->method('getServiceLocator')
            ->willReturn($serviceLocator);

        $serviceLocator
            ->expects(self::at(1))
            ->method('get')
            ->willReturn($this->createMock(GenerateService::class));
        $serviceLocator
            ->expects(self::at(2))
            ->method('get')
            ->willReturn($this->createMock(DeleteService::class));

        self::assertInstanceOf(
            GenerateController::class,
            (new GenerateControllerFactory())->createService($serviceLocator)
        );
    }
}
