<?php

namespace ConsoleTest\Factory\Controller;

use Console\Controller\ImportController;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Service\DeleteService;
use Console\Service\ImportService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Console\Factory\Controller\ImportControllerFactory
 */
class ImportControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(ImportService::class)
            ->willReturn($this->createMock(ImportService::class));
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(DeleteService::class)
            ->willReturn($this->createMock(DeleteService::class));

        self::assertInstanceOf(
            ImportController::class,
            (new ImportControllerFactory())->__invoke($serviceManager)
        );
    }
}
