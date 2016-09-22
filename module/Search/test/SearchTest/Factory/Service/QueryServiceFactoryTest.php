<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\QueryServiceFactory;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Service\QueryServiceFactory
 */
class QueryServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        self::assertInstanceOf(
            QueryService::class,
            (new QueryServiceFactory())->__invoke($serviceManager)
        );
    }
}