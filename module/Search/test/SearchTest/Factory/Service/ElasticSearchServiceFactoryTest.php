<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Service\ElasticSearchServiceFactory
 */
class ElasticSearchServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $queryServiceMock = $this->createMock(QueryService::class);

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($queryServiceMock);

        self::assertInstanceOf(
            ElasticSearchService::class,
            (new ElasticSearchServiceFactory())->__invoke($serviceManager)
        );
    }
}