<?php

namespace SyncTest\Service\Import\Event;

use Common\Constant\EEN;
use Sync\Service\Event\EventBrite;
use Sync\Service\Event\EventService;
use Sync\Service\Event\Merlin;
use Sync\Service\Event\SalesForce;
use Sync\Service\IndexService;

/**
 * @covers \Sync\Service\Event\EventService
 */
class EventServiceTest extends \PHPUnit_Framework_TestCase
{
    const PATH = '/path';
    const DATE = '2016-12-12';

    /** @var EventService $service */
    private $service;
    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexMock */
    private $indexMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Merlin $merlinMock */
    private $merlinMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|EventBrite $eventBriteMock */
    private $eventBriteMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForce $salesForceMock */
    private $salesForceMock;

    public function testImport()
    {
        $this->indexMock->expects(self::once())
            ->method('createIndex')
            ->with(EEN::ES_INDEX_EVENT);

        $this->merlinMock->expects(self::once())->method('import');
        $this->eventBriteMock->expects(self::once())->method('import');
        $this->salesForceMock->expects(self::once())->method('import');

        $this->service->import();
    }

    public function testDeleteEmpty()
    {
        $now = new \DateTime();

        $this->indexMock->expects(self::once())
            ->method('getOutOfDateData')
            ->willReturn(['hits' => ['hits' => []]]);

        $this->indexMock->expects(self::never())
            ->method('delete');
        $this->service->delete($now);
    }

    public function testDelete()
    {
        $now = new \DateTime();

        $this->indexMock->expects(self::once())
            ->method('getOutOfDateData')
            ->with(EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT, $now->format(EEN::DATE_FORMAT_IMPORT))
            ->willReturn(['hits' => ['hits' => [['_id' => '1']]]]);

        $this->indexMock->expects(self::once())
            ->method('delete')
            ->with([
                'body' => [
                    [
                        'delete' => [
                            '_index' => EEN::ES_INDEX_EVENT,
                            '_type'  => EEN::ES_TYPE_EVENT,
                            '_id'    => 1,
                        ],
                    ],
                ],
            ]);
        $this->service->delete($now);
    }

    protected function Setup()
    {
        $this->indexMock = $this->createMock(IndexService::class);
        $this->merlinMock = $this->createMock(Merlin::class);
        $this->eventBriteMock = $this->createMock(EventBrite::class);
        $this->salesForceMock = $this->createMock(SalesForce::class);

        $this->service = new EventService(
            $this->indexMock,
            $this->merlinMock,
            $this->eventBriteMock,
            $this->salesForceMock
        );
    }
}
