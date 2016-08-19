<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\IndexServiceFactory;
use Console\Service\IndexService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Console\Factory\Service\IndexServiceFactory
 */
class IndexServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        self::assertInstanceOf(
            IndexService::class,
            (new IndexServiceFactory())->createService($serviceLocator)
        );
    }
}
