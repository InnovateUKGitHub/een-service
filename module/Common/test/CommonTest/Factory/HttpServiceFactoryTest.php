<?php

namespace ConsoleTest\Factory\Service;

use Common\Factory\HttpServiceFactory;
use Common\Service\HttpService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Common\Factory\HttpServiceFactory
 */
class HttpServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        self::assertInstanceOf(
            HttpService::class,
            (new HttpServiceFactory())->__invoke($serviceManager)
        );
    }
}
