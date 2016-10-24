<?php

namespace SyncTest\Factory\Service;

use Sync\Factory\Service\PurgeServiceFactory;
use Sync\Service\PurgeService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\PurgeServiceFactory
 */
class PurgeServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        self::assertInstanceOf(
            PurgeService::class,
            (new PurgeServiceFactory())->__invoke($serviceManager)
        );
    }
}