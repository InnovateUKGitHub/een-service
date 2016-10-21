<?php

namespace CommonTest\Factory\Service;

use Common\Factory\SalesForceServiceFactory;
use Common\Service\SalesForceService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Common\Factory\SalesForceServiceFactory
 */
class SalesForceServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        self::assertInstanceOf(
            SalesForceService::class,
            (new SalesForceServiceFactory())->__invoke($serviceManager)
        );
    }
}
