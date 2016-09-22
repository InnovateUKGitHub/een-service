<?php

namespace SearchTest\Factory\Service;

use Mail\Factory\Service\MailServiceFactory;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Mail\Factory\Service\MailServiceFactory
 */
class MailServiceFactoryTest extends \PHPUnit_Framework_TestCase
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
        $serviceManager->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        self::assertInstanceOf(
            MailService::class,
            (new MailServiceFactory())->__invoke($serviceManager)
        );
    }
}