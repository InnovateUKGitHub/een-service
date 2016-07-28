<?php

namespace SearchTest\V1\Rpc\Event;

use Search\V1\ElasticSearch\Service\ElasticSearchService;
use Search\V1\Merlin\Service\MerlinService;
use Search\V1\Rpc\Opportunities\OpportunitiesController;
use Search\V1\Rpc\Opportunities\OpportunitiesControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Search\V1\Rpc\Opportunities\OpportunitiesControllerFactory
 */
class OpportunitiesControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $elasticSearchMock = self::getMock(ElasticSearchService::class, [], [], '', false);
        $merlinMock = self::getMock(MerlinService::class, [], [], '', false);

        $serviceLocatorMock = self::getMock(ServiceLocatorInterface::class, [], [], '', false);
        $serviceLocatorMock->expects(self::at(0))
            ->method('get')
            ->with(ElasticSearchService::class)
            ->willReturn($elasticSearchMock);
        $serviceLocatorMock->expects(self::at(1))
            ->method('get')
            ->with(MerlinService::class)
            ->willReturn($merlinMock);

        $controllersMock = self::getMock(ControllerManager::class, [], [], '', false);
        $controllersMock->expects(self::once())
            ->method('getServiceLocator')
            ->willReturn($serviceLocatorMock);

        $factory = new OpportunitiesControllerFactory();

        $controller = $factory->__invoke($controllersMock);
        self::assertInstanceOf(OpportunitiesController::class, $controller);
    }
}