<?php

namespace SearchTest\Factory\Service;

use Search\Factory\Service\EventsServiceFactory;
use Search\Service\EventsService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Service\EventsServiceFactory
 */
class EventsServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);
        $serviceManager->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($this->createMock(QueryService::class));

        self::assertInstanceOf(
            EventsService::class,
            (new EventsServiceFactory())->__invoke($serviceManager)
        );
    }
}