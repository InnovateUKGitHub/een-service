<?php

namespace SyncTest\Factory\Service\Event;

use Common\Service\SalesForceService;
use Sync\Factory\Service\Event\SalesForceFactory;
use Sync\Service\Event\SalesForce;
use Sync\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\Event\SalesForceFactory
 */
class SalesForceFactoryTest extends \PHPUnit_Framework_TestCase
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
            ->with(SalesForceService::class)
            ->willReturn($this->createMock(SalesForceService::class));

        self::assertInstanceOf(
            SalesForce::class,
            (new SalesForceFactory())->__invoke($serviceManager)
        );
    }
}
