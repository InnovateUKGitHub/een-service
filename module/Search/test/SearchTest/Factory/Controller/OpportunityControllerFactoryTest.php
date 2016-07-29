<?php

namespace SearchTest\Factory\Controller;

use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Search\V1\Rpc\Opportunities\OpportunitiesControllerFactory
 */
class OpportunitiesControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $elasticSearchMock = $this->createMock(ElasticSearchService::class);
        $merlinMock = $this->createMock(MerlinService::class);

        $serviceLocatorMock = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocatorMock->expects(self::at(0))
            ->method('get')
            ->with(ElasticSearchService::class)
            ->willReturn($elasticSearchMock);
        $serviceLocatorMock->expects(self::at(1))
            ->method('get')
            ->with(MerlinService::class)
            ->willReturn($merlinMock);

        /** @var \PHPUnit_Framework_MockObject_MockObject|ControllerManager $controllersMock */
        $controllersMock = $this->createMock(ControllerManager::class);
        $controllersMock->expects(self::once())
            ->method('getServiceLocator')
            ->willReturn($serviceLocatorMock);

        $factory = new OpportunitiesControllerFactory();

        $controller = $factory->__invoke($controllersMock);
        self::assertInstanceOf(OpportunitiesController::class, $controller);
    }
}