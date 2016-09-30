<?php

namespace SearchTest\Service;

use Common\Constant\EEN;
use Search\Service\OpportunitiesService;
use Search\Service\QueryService;

/**
 * @covers \Search\Service\AbstractSearchService
 * @covers \Search\Service\OpportunitiesService
 */
class OpportunitiesServiceTest extends \PHPUnit_Framework_TestCase
{
    const ID = 'id';

    public function testSearch()
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

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['success' => true], $service->search($params));
    }

    public function testSearchNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_OPPORTUNITY)
            ->willReturn(false);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['total' => 0], $service->search([]));
    }

    public function testGet()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('getDocument')
            ->with(self::ID, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(['success' => true]);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['success' => true], $service->get(self::ID));
    }

    public function testGetNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_OPPORTUNITY)
            ->willReturn(false);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['total' => 0], $service->get(self::ID));
    }
}
