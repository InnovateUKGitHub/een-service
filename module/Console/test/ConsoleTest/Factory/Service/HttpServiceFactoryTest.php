<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\HttpServiceFactory;
use Console\Service\HttpService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Service\HttpServiceFactory
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
