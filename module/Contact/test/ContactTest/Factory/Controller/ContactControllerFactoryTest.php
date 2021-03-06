<?php

namespace ContactTest\Factory\Controller;

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
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(ContactService::class)
            ->willReturn($this->createMock(ContactService::class));

        self::assertInstanceOf(
            ContactController::class,
            (new ContactControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}