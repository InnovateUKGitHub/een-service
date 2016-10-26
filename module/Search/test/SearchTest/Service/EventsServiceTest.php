<?php

namespace SearchTest\Service;

use Common\Constant\EEN;
use Search\Service\EventsService;
use Search\Service\QueryService;

/**
 * @covers \Search\Service\AbstractSearchService
 * @covers \Search\Service\EventsService
 */
class EventsServiceTest extends \PHPUnit_Framework_TestCase
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

        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('search')
            ->with($params, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT)
            ->willReturn(['success' => true]);

        $service = new EventsService($queryServiceMock);

        self::assertEquals(['success' => true], $service->search($params));
    }

    public function testSearchNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_EVENT)
            ->willReturn(false);

        $service = new EventsService($queryServiceMock);

        self::assertEquals(['total' => 0], $service->search([]));
    }

    public function testGet()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('getDocument')
            ->with(self::ID, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT)
            ->willReturn(['success' => true]);

        $service = new EventsService($queryServiceMock);

        self::assertEquals(['success' => true], $service->get(self::ID));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetNoIndex()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
        $queryServiceMock = $this->createMock(QueryService::class);
        $queryServiceMock->expects(self::once())
            ->method('exists')
            ->with(EEN::ES_INDEX_EVENT)
            ->willReturn(false);

        $service = new EventsService($queryServiceMock);

        $service->get(self::ID);
    }
}
