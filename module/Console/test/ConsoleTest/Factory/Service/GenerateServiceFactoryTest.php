<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\GenerateServiceFactory;
use Console\Service\GenerateService;
use Console\Service\IndexService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Console\Factory\Service\GenerateServiceFactory
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
