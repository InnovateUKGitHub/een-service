<?php

namespace ConsoleTest\Factory\Controller;

use Console\Controller\GenerateController;
use Console\Factory\Controller\GenerateControllerFactory;
use Console\Service\GenerateService;
use Console\Service\PurgeService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Controller\GenerateControllerFactory
 */
class GenerateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(GenerateService::class)
            ->willReturn($this->createMock(GenerateService::class));
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(PurgeService::class)
            ->willReturn($this->createMock(PurgeService::class));

        self::assertInstanceOf(
            GenerateController::class,
            (new GenerateControllerFactory())->__invoke($serviceManager)
        );
    }
}
