<?php

namespace SyncTest\Service\Import\Event;

use Common\Constant\EEN;
use Common\Service\SalesForceService;
use Sync\Service\Event\SalesForce;
use Sync\Service\IndexService;

/**
 * @covers \Sync\Service\Event\SalesForce
 */
class SalesForceTest extends \PHPUnit_Framework_TestCase
{
    const PATH = '/path';
    const DATE = '2016-12-12';

    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexMock */
    private $indexMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForceService $salesForceMock */
    private $salesForceMock;
    /** @var SalesForce $service */
    private $service;

    public function testImport()
    {
        $this->salesForceMock->expects(self::once())
            ->method('describesObject')
            ->with('Event__c')
            ->willReturn([]);
        $query = new \stdClass();
        $query->queryString = "
SELECT 
FROM Event__c
WHERE Start_Date_time__c >= TODAY
";
        $this->salesForceMock->expects(self::once())
            ->method('query')
            ->with($query)
            ->willReturn([
                'size'    => 1,
                'records' => [
                    [
                        'Id'                     => 1,
                        'Title__c'               => 'Title',
                        'Event_Summary__c'       => 'Summary',
                        'Event_Description__c'   => 'Description',
                        'Start_Date_time__c'     => 'Start',
                        'End_Date_Time__c'       => 'End',
                        'Destination_Country__c' => 'Country',
                        'Attendance_Fee__c'      => '100',
                    ],
                ],
            ]);

        $params = [
            'title'       => 'Title',
            'summary'     => 'Summary',
            'description' => 'Description',
            'start_date'  => 'Start',
            'end_date'    => 'End',
            'country'     => 'Country',
            'fee'         => '100',
            'type'        => 'salesForce',
            'date_import' => self::DATE,
        ];

        $this->indexMock->expects(self::once())
            ->method('index')
            ->with($params, 1, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);

        $this->service->import(self::DATE);
    }

    public function testImportNoResult()
    {
        $this->salesForceMock->expects(self::once())
            ->method('describesObject')
            ->with('Event__c')
            ->willReturn([]);

        $this->salesForceMock->expects(self::once())
            ->method('query')
            ->willReturn(['size' => 0]);

        $this->indexMock->expects(self::never())
            ->method('index');

        $this->service->import(self::DATE);
    }

    protected function Setup()
    {
        $this->indexMock = $this->createMock(IndexService::class);
        $this->salesForceMock = $this->createMock(SalesForceService::class);

        $this->service = new SalesForce($this->indexMock, $this->salesForceMock);
    }
}
