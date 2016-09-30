<?php

namespace SearchTest\Factory\Controller;

use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Service\OpportunitiesService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Controller\OpportunitiesControllerFactory
 */
class OpportunitiesControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(OpportunitiesService::class)
            ->willReturn($this->createMock(OpportunitiesService::class));

        self::assertInstanceOf(
            OpportunitiesController::class,
            (new OpportunitiesControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}