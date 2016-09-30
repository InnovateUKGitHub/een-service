<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\OpportunitiesServiceFactory;
use Search\Service\OpportunitiesService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Service\OpportunitiesServiceFactory
 */
class OpportunitiesServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($this->createMock(QueryService::class));

        self::assertInstanceOf(
            OpportunitiesService::class,
            (new OpportunitiesServiceFactory())->__invoke($serviceManager)
        );
    }
}