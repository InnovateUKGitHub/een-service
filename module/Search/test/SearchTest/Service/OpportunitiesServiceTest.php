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

    public function testCount()
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
            ->method('count')
            ->with(EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(10);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(10, $service->count($params));
    }

    public function testCountNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_OPPORTUNITY)
            ->willReturn(false);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['total' => 0], $service->count([]));
    }

    public function testSearchType1()
    {
        $params = [
            'from'             => 0,
            'size'             => 10,
            'search'           => 'Some Search',
            'opportunity_type' => ['BO'],
            'country'          => ['FR'],
            'type'             => 1,
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::at(1))
            ->method('getDocument')
            ->with($params['search'], EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willThrowException(new \Exception());
        $queryServiceMock->expects(self::at(2))
            ->method('mustQueryString')
            ->with(['title^3', 'summary^2', 'description^1'], ['Some', 'Search']);
        $queryServiceMock->expects(self::once())
            ->method('search')
            ->with($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(['success' => true]);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['success' => true], $service->search($params));
    }

    public function testSearchType2()
    {
        $params = [
            'type'             => 2,
            'from'             => 0,
            'size'             => 10,
            'search'           => 'Some Search',
            'opportunity_type' => ['BO'],
            'country'          => ['FR'],
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::at(1))
            ->method('getDocument')
            ->with($params['search'], EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willThrowException(new \Exception());
        $queryServiceMock->expects(self::at(2))
            ->method('mustFuzzy')
            ->with(['title^3', 'summary^2', 'description^1'], ['Some', 'Search']);
        $queryServiceMock->expects(self::once())
            ->method('search')
            ->with($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(['success' => true]);

        $service = new OpportunitiesService($queryServiceMock);

        self::assertEquals(['success' => true], $service->search($params));
    }

    public function testSearchType3()
    {
        $params = [
            'type'             => 3,
            'from'             => 0,
            'size'             => 10,
            'search'           => 'Some Search',
            'opportunity_type' => ['BO'],
            'country'          => ['FR'],
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::at(1))
            ->method('getDocument')
            ->with($params['search'], EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willThrowException(new \Exception());
        $queryServiceMock->expects(self::at(2))
            ->method('mustMatchPhrase')
            ->with(['title^3', 'summary^2', 'description^1'], 'Some Search');
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
