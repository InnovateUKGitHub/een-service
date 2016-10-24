<?php

namespace SyncTest\Factory\Controller;

use Sync\Controller\IndexController;
use Sync\Factory\Controller\IndexControllerFactory;
use Sync\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Controller\IndexControllerFactory
 */
class IndexControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::once())
            ->method('get')
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));

        self::assertInstanceOf(
            IndexController::class,
            (new IndexControllerFactory())->__invoke($serviceManager)
        );
    }
}
