<?php

namespace ContactTest\Factory\Controller;

use Contact\Controller\EoiController;
use Contact\Factory\Controller\EoiControllerFactory;
use Contact\Service\EoiService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Controller\EoiControllerFactory
 */
class EoiControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(EoiService::class)
            ->willReturn($this->createMock(EoiService::class));

        self::assertInstanceOf(
            EoiController::class,
            (new EoiControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}