<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\QueryServiceFactory;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Search\V1\ElasticSearch\Factory\QueryServiceFactory
 */
class QueryServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ServiceLocatorInterface $serviceLocatorMock */
        $serviceLocatorMock = self::getMock(ServiceLocatorInterface::class, [], [], '', false);
        $factory = new QueryServiceFactory();
        $service = $factory->createService($serviceLocatorMock);

        self::assertInstanceOf(QueryService::class, $service);
    }
}