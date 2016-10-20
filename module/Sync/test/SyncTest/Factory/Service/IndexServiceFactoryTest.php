<?php

namespace SyncTest\Factory\Service;

use Common\Constant\EEN;
use Sync\Factory\Service\IndexServiceFactory;
use Sync\Service\IndexService;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\IndexServiceFactory
 */
class IndexServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $config = [
            EEN::ELASTIC_SEARCH_INDEXES => '',
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::assertInstanceOf(
            IndexService::class,
            (new IndexServiceFactory())->__invoke($serviceManager)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the elastic-search indexes information
     */
    public function testFactoryThrowException()
    {
        $config = [];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        (new IndexServiceFactory())->__invoke($serviceManager);
    }
}
