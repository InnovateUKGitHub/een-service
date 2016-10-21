<?php

namespace ContactTest\Factory\Controller;

use Contact\Controller\EmailController;
use Contact\Factory\Controller\EmailControllerFactory;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Controller\EmailControllerFactory
 */
class EmailControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(MailService::class)
            ->willReturn($this->createMock(MailService::class));

        self::assertInstanceOf(
            EmailController::class,
            (new EmailControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}