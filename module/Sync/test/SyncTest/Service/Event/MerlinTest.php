<?php

namespace SyncTest\Service\Event;

use Common\Constant\EEN;
use Sync\Service\Event\Merlin;
use Sync\Service\Event\MerlinConnection;
use Sync\Service\IndexService;
use Sync\Validator\MerlinValidator;

/**
 * @covers \Sync\Service\Event\Merlin
 */
class MerlinTest extends \PHPUnit_Framework_TestCase
{
    const STRUCTURE = [];
    const DATE = '2016-12-12';

    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexMock */
    private $indexMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|MerlinConnection $connectionMock */
    private $connectionMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|MerlinValidator $validatorMock */
    private $validatorMock;
    /** @var Merlin $service */
    private $service;

    public function testGetList()
    {
        $xml = file_get_contents(__DIR__ . '/merlin-event.xml');
        $results = simplexml_load_string($xml);

        $this->connectionMock->expects(self::once())
            ->method('getList')
            ->willReturn($results);

        $this->validatorMock->expects(self::at(0))
            ->method('checkEventsExists')
            ->with($results);
        $this->validatorMock->expects(self::at(1))
            ->method('checkDataExists')
            ->with($results->{'event'}, self::STRUCTURE);

        $this->indexMock->expects(self::once())
            ->method('index')
            ->with(
                [
                    'title'        => (string)$results->{'event'}->{'EventTitle'}->__toString() ?: null,
                    'description'  => (string)$results->{'event'}->{'Description'}->__toString() ?: null,
                    'start_date'   => (string)$results->{'event'}->{'EventStartDate'}->__toString() ?: null,
                    'end_date'     => (string)$results->{'event'}->{'EventEndDate'}->__toString() ?: null,
                    'country_code' => (string)$results->{'event'}->{'CountryISO'}->__toString() ?: null,
                    'country'      => (string)$results->{'event'}->{'Country'}->__toString() ?: null,
                    'city'         => (string)$results->{'event'}->{'City'}->__toString() ?: null,
                    'url'          => (string)$results->{'event'}->{'location_website'},
                    'type'         => 'merlin',
                    'date_import'  => self::DATE,
                ],
                sha1((string)$results->{'event'}->{'Created'}->__toString()),
                EEN::ES_INDEX_EVENT,
                EEN::ES_TYPE_EVENT
            );

        $this->service->import(self::DATE);
    }

    protected function Setup()
    {
        $this->indexMock = $this->createMock(IndexService::class);
        $this->connectionMock = $this->createMock(MerlinConnection::class);
        $this->validatorMock = $this->createMock(MerlinValidator::class);

        $this->service = new Merlin(
            $this->indexMock,
            $this->connectionMock,
            $this->validatorMock,
            self::STRUCTURE
        );
    }
}
