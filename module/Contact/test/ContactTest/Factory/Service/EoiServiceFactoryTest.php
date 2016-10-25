<?php

namespace ContactTest\Factory\Service;

use Common\Service\SalesForceService;
use Contact\Factory\Service\EoiServiceFactory;
use Contact\Factory\Service\LeadServiceFactory;
use Contact\Service\EoiService;
use Contact\Service\LeadService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Service\EoiServiceFactory
 */
class EoiServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(SalesForceService::class)
            ->willReturn($this->createMock(SalesForceService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($this->createMock(QueryService::class));

        self::assertInstanceOf(
            EoiService::class,
            (new EoiServiceFactory())->__invoke($serviceManager)
        );
    }
}