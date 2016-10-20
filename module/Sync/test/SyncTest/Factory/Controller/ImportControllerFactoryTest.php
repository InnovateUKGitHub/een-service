<?php

namespace SyncTest\Factory\Controller;

use Sync\Controller\ImportController;
use Sync\Factory\Controller\ImportControllerFactory;
use Sync\Service\DeleteService;
use Sync\Service\ImportService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Controller\ImportControllerFactory
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
