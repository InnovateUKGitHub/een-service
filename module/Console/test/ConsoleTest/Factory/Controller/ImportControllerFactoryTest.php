<?php

namespace ConsoleTest\Factory\Controller;

use Console\Controller\ImportController;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Service\ImportService;
use Zend\Mvc\Controller\ControllerManager;

/**
 * @covers Console\Factory\Controller\ImportControllerFactory
 */
class ImportControllerFactoryTest extends \PHPUnit_Framework_TestCase
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
            ->willReturn($this->createMock(ImportService::class));

        self::assertInstanceOf(
            ImportController::class,
            (new ImportControllerFactory())->createService($serviceLocator)
        );
    }
}
