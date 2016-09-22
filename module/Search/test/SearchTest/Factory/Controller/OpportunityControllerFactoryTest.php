<?php

namespace SearchTest\Factory\Controller;

use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Service\ElasticSearchService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Controller\OpportunitiesControllerFactory
 */
class OpportunitiesControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $elasticSearchMock = $this->createMock(ElasticSearchService::class);

        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(ElasticSearchService::class)
            ->willReturn($elasticSearchMock);

        self::assertInstanceOf(
            OpportunitiesController::class,
            (new OpportunitiesControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}