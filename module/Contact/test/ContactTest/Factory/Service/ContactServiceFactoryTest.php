<?php

namespace ContactTest\Factory\Service;

use Console\Service\HttpService;
use Contact\Factory\Service\ContactServiceFactory;
use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Service\ContactServiceFactory
 */
class ContactServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $queryServiceMock = $this->createMock(HttpService::class);

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(HttpService::class)
            ->willReturn($queryServiceMock);

        self::assertInstanceOf(
            ContactService::class,
            (new ContactServiceFactory())->__invoke($serviceManager)
        );
    }
}