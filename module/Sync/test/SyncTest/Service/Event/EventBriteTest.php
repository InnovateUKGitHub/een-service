<?php

namespace SyncTest\Service\Import\Event;

use Common\Constant\EEN;
use Common\Service\HttpService;
use Sync\Service\Event\EventBrite;
use Sync\Service\IndexService;
use Zend\Http\Request;
use Zend\Log\Logger;

/**
 * @covers \Sync\Service\Event\EventBrite
 */
class EventBriteTest extends \PHPUnit_Framework_TestCase
{
    const PATH = '/path';
    const DATE = '2016-12-12';

    /** @var \PHPUnit_Framework_MockObject_MockObject|HttpService $clientMock */
    private $clientMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Logger $loggerMock */
    private $loggerMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexMock */
    private $indexMock;
    /** @var EventBrite $service */
    private $service;

    public function testImport()
    {
        $result = [
            'events' => [
                [
                    'id'          => 1,
                    'name'        => ['text' => 'Name'],
                    'description' => ['text' => 'Description'],
                    'start'       => ['utc' => 'Start'],
                    'end'         => ['utc' => 'End'],
                    'url'         => 'Url',
                ],
            ],
        ];

        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willReturn($result);
        $this->indexMock->expects(self::once())
            ->method('index')
            ->with([
                'title'        => 'Name',
                'summary'      => 'Description',
                'description'  => 'Description',
                'start_date'   => 'Start',
                'end_date'     => 'End',
                'url'          => 'Url',
                'country_code' => 'GB',
                'country'      => 'United Kingdom',
                'type'         => 'eventBrite',
                'date_import'  => self::DATE,
            ], 1, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);

        $this->service->import(self::DATE);
    }

    protected function Setup()
    {
        $this->clientMock = $this->createMock(HttpService::class);
        $this->indexMock = $this->createMock(IndexService::class);
        $this->loggerMock = $this->createMock(Logger::class);

        $this->service = new EventBrite(
            $this->clientMock,
            $this->indexMock,
            $this->loggerMock,
            self::PATH
        );
    }
}
