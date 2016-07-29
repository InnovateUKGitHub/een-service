<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Search\Factory\Service\ElasticSearchServiceFactory
 */
class ElasticSearchServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $queryServiceMock = $this->createMock(QueryService::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|ServiceLocatorInterface $serviceLocatorMock */
        $serviceLocatorMock = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocatorMock->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($queryServiceMock);

        $factory = new ElasticSearchServiceFactory();

        $service = $factory->createService($serviceLocatorMock);
        self::assertInstanceOf(ElasticSearchService::class, $service);
    }
}