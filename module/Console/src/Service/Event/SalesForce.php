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

        foreach ($events['records'] as $event) {
            $event = (array)$event;

            $params = [
                'name'         => $event['Name'],
                'start_date'   => $event['Start_Date_time__c'],
                'end_date'     => isset($event['End_Date_Time__c']) ? $event['End_Date_Time__c'] : $event['Start_Date_time__c'],
                'date_import'  => $dateImport,
                'url'          => 'een',

                // Temporary information
                'country_code' => 'PL',
                'country'      => 'Poland',
                'description'  => 'Enterprise Europe Network is running a company mission in Poland (Poznan & Krakow) from...',
            ];
if ($event['Name'] == 'EV-000001') {
    var_dump($event);
}
            if (isset($event['Title__c'])) {
                $params['title'] = $event['Title__c'];
            }
            if (isset($event['Event_Type__c'])) {
                $params['type'] = $event['Event_Type__c'];
            }
            if (isset($event['Attendance_Fee__c'])) {
                $params['fee'] = $event['Attendance_Fee__c'];
            }
            if (isset($event['Event_Summary__c'])) {
                $params['summary'] = $event['Event_Summary__c'];
            }
            if (isset($event['Event_Description__c'])) {
                $params['description'] = $event['Event_Description__c'];
            }

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
        $fields = array_keys($this->salesForce->describesObject('Event__c'));
        var_dump($fields);
        $fields = implode(', ', $fields);
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