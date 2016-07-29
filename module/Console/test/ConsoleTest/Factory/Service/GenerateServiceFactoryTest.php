<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\GenerateServiceFactory;
use Console\Service\GenerateService;
use Console\Service\IndexService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Console\Factory\Service\GenerateServiceFactory
 */
class GenerateServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        $serviceLocator
            ->expects(self::once())
            ->method('get')
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));

        self::assertInstanceOf(
            GenerateService::class,
            (new GenerateServiceFactory())->createService($serviceLocator)
        );
    }
}
