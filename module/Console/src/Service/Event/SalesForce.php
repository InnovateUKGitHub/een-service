<?php

namespace Console\Service\Event;

use Common\Constant\EEN;
use Common\Service\SalesForceService;
use Console\Service\IndexService;
use ZF\ApiProblem\ApiProblemResponse;

class SalesForce
{
    /** @var IndexService */
    private $indexService;
    /** @var SalesForceService */
    private $salesForce;

    /**
     * SalesForce constructor.
     *
     * @param IndexService      $indexService
     * @param SalesForceService $salesForce
     */
    public function __construct(IndexService $indexService, SalesForceService $salesForce)
    {
        $this->indexService = $indexService;
        $this->salesForce = $salesForce;
    }

    /**
     * @param \DateTime $dateImport
     */
    public function import($dateImport)
    {
        $events = $this->getEvents();

        if ($events['size'] == 0) {
            return;
        }

        foreach ($events['records'] as $event) {
            $event = (array)$event;

            $params = [
                'title'       => $event['Title__c'],
                'summary'     => isset($event['Event_Summary__c']) ? $event['Event_Summary__c'] : null,
                'description' => isset($event['Event_Description__c']) ? $event['Event_Description__c'] : null,
                'start_date'  => $event['Start_Date_time__c'],
                'end_date'    => isset($event['End_Date_Time__c']) ? $event['End_Date_Time__c'] : $event['Start_Date_time__c'],
                'country'     => isset($event['Destination_Country__c']) ? $event['Destination_Country__c'] : null,
                'fee'         => isset($event['Attendance_Fee__c']) ? $event['Attendance_Fee__c'] : 0,
                'type'        => 'salesForce',
                'date_import' => $dateImport,
            ];

            $this->indexService->index(
                $params,
                $event['Id'],
                EEN::ES_INDEX_EVENT,
                EEN::ES_TYPE_EVENT
            );
        }
    }

    private function getEvents()
    {
        $fields = implode(', ', array_keys($this->salesForce->describesObject('Event__c')));

        $query = new \stdClass();
        $query->queryString = "
SELECT $fields
FROM Event__c
WHERE Start_Date_time__c >= TODAY
";

        $result = $this->salesForce->query($query);
        if ($result instanceof ApiProblemResponse) {
            throw new \RuntimeException($result->getApiProblem()->toArray()['exception']);
        }

        return (array)$result;
    }
}