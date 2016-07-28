<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Search\V1\ElasticSearch\Factory\ElasticSearchServiceFactory
 */
class ElasticSearchServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $queryServiceMock = self::getMock(QueryService::class, [], [], '', false);

        /** @var \PHPUnit_Framework_MockObject_MockObject|ServiceLocatorInterface $serviceLocatorMock */
        $serviceLocatorMock = self::getMock(ServiceLocatorInterface::class, [], [], '', false);
        $serviceLocatorMock->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($queryServiceMock);

        $factory = new ElasticSearchServiceFactory();

        $service = $factory->createService($serviceLocatorMock);
        self::assertInstanceOf(ElasticSearchService::class, $service);
    }
}