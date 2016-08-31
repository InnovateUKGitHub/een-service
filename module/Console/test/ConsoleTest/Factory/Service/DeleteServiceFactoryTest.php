<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\DeleteServiceFactory;
use Console\Service\DeleteService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Service\DeleteServiceFactory
 */
class DeleteServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        self::assertInstanceOf(
            DeleteService::class,
            (new DeleteServiceFactory())->__invoke($serviceManager)
        );
    }
}
