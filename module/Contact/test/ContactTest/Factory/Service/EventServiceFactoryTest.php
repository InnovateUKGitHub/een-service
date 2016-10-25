<?php

namespace ContactTest\Factory\Service;

use Common\Service\SalesForceService;
use Contact\Factory\Service\EoiServiceFactory;
use Contact\Factory\Service\EventServiceFactory;
use Contact\Factory\Service\LeadServiceFactory;
use Contact\Service\EoiService;
use Contact\Service\EventService;
use Contact\Service\LeadService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Service\EventServiceFactory
 */
class EventServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(SalesForceService::class)
            ->willReturn($this->createMock(SalesForceService::class));

        self::assertInstanceOf(
            EventService::class,
            (new EventServiceFactory())->__invoke($serviceManager)
        );
    }
}