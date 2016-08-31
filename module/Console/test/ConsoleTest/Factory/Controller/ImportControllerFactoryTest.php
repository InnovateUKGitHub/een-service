<?php

namespace ConsoleTest\Factory\Controller;

use Console\Controller\ImportController;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Service\ImportService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Controller\ImportControllerFactory
 */
class ImportControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::once())
            ->method('get')
            ->willReturn($this->createMock(ImportService::class));

        self::assertInstanceOf(
            ImportController::class,
            (new ImportControllerFactory())->__invoke($serviceManager)
        );
    }
}
