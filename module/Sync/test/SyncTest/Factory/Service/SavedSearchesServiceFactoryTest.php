<?php

namespace SyncTest\Factory\Service;

use Common\Service\SalesForceService;
use Search\Service\QueryService;
use Sync\Factory\Service\SavedSearchesServiceFactory;
use Sync\Service\SavedSearchesService;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Renderer\PhpRenderer;

/**
 * @covers \Sync\Factory\Service\SavedSearchesServiceFactory
 */
class SavedSearchesServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(QueryService::class)
            ->willReturn(self::createMock(QueryService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(SalesForceService::class)
            ->willReturn(self::createMock(SalesForceService::class));
        $serviceManager->expects(self::at(2))
            ->method('get')
            ->with(PhpRenderer::class)
            ->willReturn(self::createMock(PhpRenderer::class));
        self::assertInstanceOf(
            SavedSearchesService::class,
            (new SavedSearchesServiceFactory())->__invoke($serviceManager)
        );
    }
}