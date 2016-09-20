<?php

namespace ContactTest\Factory\Service;

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
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([
                'url' => 'https://cs87.salesforce.com',
            ]);

        self::assertInstanceOf(
            ContactService::class,
            (new ContactServiceFactory())->__invoke($serviceManager)
        );
    }
}