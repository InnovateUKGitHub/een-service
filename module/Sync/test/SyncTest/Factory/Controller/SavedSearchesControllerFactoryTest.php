<?php

namespace SyncTest\Factory\Controller;

use Sync\Controller\SavedSearchesController;
use Sync\Factory\Controller\SavedSearchesControllerFactory;
use Sync\Service\SavedSearchesService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Controller\SavedSearchesControllerFactory
 */
class SavedSearchesControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::once())
            ->method('get')
            ->with(SavedSearchesService::class)
            ->willReturn($this->createMock(SavedSearchesService::class));

        self::assertInstanceOf(
            SavedSearchesController::class,
            (new SavedSearchesControllerFactory())->__invoke($serviceManager)
        );
    }
}
