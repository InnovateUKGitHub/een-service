<?php

namespace SearchTest\Factory\Controller;

use Contact\Controller\ContactController;
use Contact\Factory\Controller\ContactControllerFactory;
use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Controller\ContactControllerFactory
 */
class ContactControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $service = $this->createMock(ContactService::class);

        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(ContactService::class)
            ->willReturn($service);

        self::assertInstanceOf(
            ContactController::class,
            (new ContactControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}