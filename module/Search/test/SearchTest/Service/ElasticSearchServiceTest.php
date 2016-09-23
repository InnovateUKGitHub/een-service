<?php

namespace SearchTest\Service;

use Common\Constant\EEN;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;

/**
 * @covers \Search\Service\ElasticSearchService
 */
class ElasticSearchServiceTest extends \PHPUnit_Framework_TestCase
{
    const OPPORTUNITY_ID = 'myId';

    public function testSearchOpportunities()
    {
        $params = [
            'from'             => 0,
            'size'             => 10,
            'search'           => 'Some Search',
            'opportunity_type' => [],
            'type'             => 1,
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('getDocument')
            ->with($params['search'], EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willThrowException(new \Exception());
        $queryServiceMock->expects(self::once())
            ->method('search')
            ->with($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(['success' => true]);

        $service = new ElasticSearchService($queryServiceMock);

        self::assertEquals(['success' => true], $service->searchOpportunities($params));
    }

    public function testSearchOpportunitiesNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_OPPORTUNITY)
            ->willReturn(false);

        $service = new ElasticSearchService($queryServiceMock);

        self::assertEquals(['error' => 'Index not created'], $service->searchOpportunities([]));
    }

    public function testGetOpportunity()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('getDocument')
            ->with(self::OPPORTUNITY_ID, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(['success' => true]);

        $service = new ElasticSearchService($queryServiceMock);

        self::assertEquals(['success' => true], $service->getOpportunity(self::OPPORTUNITY_ID));
    }

    public function testGetOpportunityNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_OPPORTUNITY)
            ->willReturn(false);

        $service = new ElasticSearchService($queryServiceMock);

        self::assertEquals(['error' => 'Index not created'], $service->getOpportunity(self::OPPORTUNITY_ID));
    }
}
