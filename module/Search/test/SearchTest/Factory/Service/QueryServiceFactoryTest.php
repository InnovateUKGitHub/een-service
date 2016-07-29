<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\QueryServiceFactory;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Search\Factory\Service\QueryServiceFactory
 */
class QueryServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ServiceLocatorInterface $serviceLocatorMock */
        $serviceLocatorMock = $this->createMock(ServiceLocatorInterface::class);
        $factory = new QueryServiceFactory();
        $service = $factory->createService($serviceLocatorMock);

        self::assertInstanceOf(QueryService::class, $service);
    }
}