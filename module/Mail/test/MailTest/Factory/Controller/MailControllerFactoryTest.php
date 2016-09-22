<?php

namespace MailTest\Factory\Controller;

use Mail\Controller\MailController;
use Mail\Factory\Controller\MailControllerFactory;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Mail\Factory\Controller\MailControllerFactory
 */
class MailControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(MailService::class)
            ->willReturn($this->createMock(MailService::class));

        self::assertInstanceOf(
            MailController::class,
            (new MailControllerFactory())->__invoke($serviceManager)
        );
    }
}