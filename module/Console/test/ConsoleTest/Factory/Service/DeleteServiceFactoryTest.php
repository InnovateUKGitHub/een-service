<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\DeleteServiceFactory;
use Console\Service\DeleteService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Console\Factory\Service\DeleteServiceFactory
 */
class DeleteServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        self::assertInstanceOf(
            DeleteService::class,
            (new DeleteServiceFactory())->createService($serviceLocator)
        );
    }
}
