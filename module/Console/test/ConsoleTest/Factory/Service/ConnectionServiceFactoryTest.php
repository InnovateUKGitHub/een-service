<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\ConnectionServiceFactory;
use Console\Service\ConnectionService;
use Console\Service\HttpService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Exception\InvalidArgumentException;

/**
 * @covers Console\Factory\Service\ConnectionServiceFactory
 */
class ConnectionServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryWithCorrectConfig()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = self::getMock(
            ServiceLocatorInterface::class,
            ['get', 'has'],
            [],
            '',
            false
        );

        $serviceLocator
            ->expects(self::at(0))
            ->method('get')
            ->willReturn([
                ConnectionServiceFactory::CONFIG_ELASTIC_SEARCH => [
                    ConnectionService::SERVER => '',
                    ConnectionService::PORT => '',
                    ConnectionService::ACCEPT => '',
                    ConnectionService::CONTENT_TYPE => '',
                ]
            ]);

        $serviceLocator
            ->expects(self::at(1))
            ->method('get')
            ->willReturn($this->getMock(HttpService::class, [], [], '', false));

        self::assertInstanceOf(
            ConnectionService::class,
            (new ConnectionServiceFactory())->createService($serviceLocator)
        );
    }


    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the server information
     */
    public function testFactoryWithNoElasticSearchConfig()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = self::getMock(
            ServiceLocatorInterface::class,
            ['get', 'has'],
            [],
            '',
            false
        );

        $serviceLocator
            ->expects(self::at(0))
            ->method('get')
            ->willReturn([]);

        self::assertInstanceOf(
            ConnectionService::class,
            (new ConnectionServiceFactory())->createService($serviceLocator)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the server
     */
    public function testFactoryWithNoServerInConfig()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = self::getMock(
            ServiceLocatorInterface::class,
            ['get', 'has'],
            [],
            '',
            false
        );

        $serviceLocator
            ->expects(self::at(0))
            ->method('get')
            ->willReturn([
                ConnectionServiceFactory::CONFIG_ELASTIC_SEARCH => [
                    ConnectionService::PORT => '',
                ]
            ]);

        $serviceLocator
            ->expects(self::at(1))
            ->method('get')
            ->willReturn($this->getMock(HttpService::class, [], [], '', false));

        self::assertInstanceOf(
            ConnectionService::class,
            (new ConnectionServiceFactory())->createService($serviceLocator)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the port
     */
    public function testFactoryWithNoPortInConfig()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = self::getMock(
            ServiceLocatorInterface::class,
            ['get', 'has'],
            [],
            '',
            false
        );

        $serviceLocator
            ->expects(self::at(0))
            ->method('get')
            ->willReturn([
                ConnectionServiceFactory::CONFIG_ELASTIC_SEARCH => [
                    ConnectionService::SERVER => '',
                ]
            ]);

        $serviceLocator
            ->expects(self::at(1))
            ->method('get')
            ->willReturn($this->getMock(HttpService::class, [], [], '', false));

        self::assertInstanceOf(
            ConnectionService::class,
            (new ConnectionServiceFactory())->createService($serviceLocator)
        );
    }
}
