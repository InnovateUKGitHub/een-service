<?php

namespace SyncTest\Factory\Service;

use Sync\Factory\Service\GenerateServiceFactory;
use Sync\Service\GenerateService;
use Sync\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\GenerateServiceFactory
 */
class GenerateServiceFactoryTest extends \PHPUnit_Framework_TestCase
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
            GenerateService::class,
            (new GenerateServiceFactory())->__invoke($serviceManager)
        );
    }
}
