<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\IndexServiceFactory;
use Console\Service\IndexService;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Service\IndexServiceFactory
 */
class IndexServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $config = [
            IndexServiceFactory::ELASTIC_SEARCH => '',
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
            ->with(IndexServiceFactory::CONFIG)
            ->willReturn($config);

        self::assertInstanceOf(
            IndexService::class,
            (new IndexServiceFactory())->__invoke($serviceManager)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the elastic-search information
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
            ->with(IndexServiceFactory::CONFIG)
            ->willReturn($config);

        (new IndexServiceFactory())->__invoke($serviceManager);
    }
}
