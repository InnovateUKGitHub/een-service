<?php

namespace ContactTest\Factory\Service;

use Common\Service\SalesForceService;
use Contact\Factory\Service\LeadServiceFactory;
use Contact\Service\LeadService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Service\LeadServiceFactory
 */
class LeadServiceFactoryTest extends \PHPUnit_Framework_TestCase
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
            LeadService::class,
            (new LeadServiceFactory())->__invoke($serviceManager)
        );
    }
}