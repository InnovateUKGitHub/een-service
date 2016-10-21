<?php

namespace MailTest\Factory\Service;

use Common\Service\HttpService;
use Mail\Factory\Service\TemplateServiceFactory;
use Mail\Service\TemplateService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Mail\Factory\Service\TemplateServiceFactory
 */
class TemplateServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $config = [
            'gov-delivery' => [
                'server' => '',
                'token'  => '',
                'scheme' => '',
            ],
        ];

        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(HttpService::class)
            ->willReturn($this->createMock(HttpService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with('config')
            ->willReturn($config);

        self::assertInstanceOf(
            TemplateService::class,
            (new TemplateServiceFactory())->__invoke($serviceManager)
        );
    }
}