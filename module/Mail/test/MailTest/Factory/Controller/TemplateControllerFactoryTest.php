<?php

namespace MailTest\Factory\Controller;

use Mail\Controller\TemplateController;
use Mail\Factory\Controller\TemplateControllerFactory;
use Mail\Service\TemplateService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Mail\Factory\Controller\TemplateControllerFactory
 */
class TemplateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(TemplateService::class)
            ->willReturn($this->createMock(TemplateService::class));

        self::assertInstanceOf(
            TemplateController::class,
            (new TemplateControllerFactory())->__invoke($serviceManager)
        );
    }
}