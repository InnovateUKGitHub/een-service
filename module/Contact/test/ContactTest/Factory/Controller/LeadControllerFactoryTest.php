<?php

namespace ContactTest\Factory\Controller;

use Contact\Controller\LeadController;
use Contact\Factory\Controller\LeadControllerFactory;
use Contact\Service\LeadService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Controller\LeadControllerFactory
 */
class LeadControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(LeadService::class)
            ->willReturn($this->createMock(LeadService::class));

        self::assertInstanceOf(
            LeadController::class,
            (new LeadControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}