<?php

namespace ConsoleTest\Service;

use Search\Service\ElasticSearchService;
use Search\Service\QueryService;

/**
 * @covers Search\Service\ElasticSearchService
 */
class ElasticSearchServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSearchOpportunities()
    {
        $params = [
            'from' => 0,
            'size' => 10,
            'search' => 'Some Search'
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('search')
            ->with($params, ElasticSearchService::OPPORTUNITY, ElasticSearchService::OPPORTUNITY)
            ->willReturn(['success' => true]);

        $service = new ElasticSearchService($queryServiceMock);

        self::assertEquals(['success' => true], $service->searchOpportunities($params));
    }

    public function testSearchEvent()
    {
        $params = [
            'from' => 0,
            'size' => 10,
            'search' => 'Some Search'
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('search')
            ->with($params, ElasticSearchService::EVENT, ElasticSearchService::EVENT)
            ->willReturn(['success' => true]);

        $service = new ElasticSearchService($queryServiceMock);

        self::assertEquals(['success' => true], $service->searchEvent($params));
    }
}
