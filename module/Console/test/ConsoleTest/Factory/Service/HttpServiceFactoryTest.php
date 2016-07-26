<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\HttpServiceFactory;
use Console\Service\HttpService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Console\Factory\Service\HttpServiceFactory
 */
class HttpServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = self::getMock(ServiceLocatorInterface::class, [], [], '', false);

        self::assertInstanceOf(
            HttpService::class,
            (new HttpServiceFactory())->createService($serviceLocator)
        );
    }
}
