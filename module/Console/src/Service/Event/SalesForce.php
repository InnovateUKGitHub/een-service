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
                'title'        => $event['Name'],
                'start_date'   => $event['Start_Date_time__c'],
                'end_date'     => isset($event['End_Date_Time__c']) ? $event['End_Date_Time__c'] : $event['Start_Date_time__c'],
                'date_import'  => $dateImport,
                'url'          => 'een',

                // Temporary information
                'fee'          => '100',
                'country_code' => 'PL',
                'country'      => 'Poland',
                'summary'      => 'Make the most of an exclusive opportunity to grow your food & drink',
                'description'  => 'Enterprise Europe Network is running a company mission in Poland (Poznan & Krakow) from...',
            ];

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
        $query = new \stdClass();
        $query->queryString = '
SELECT e.Id, e.Name, e.Event_Type__c, e.Event_Registration_Status__c, e.Event_Category__c, e.Event_Sub_Category__c,
e.Start_Date_time__c, e.End_Date_Time__c, e.Attendance_Fee__c, e.Venue__c, e.Destination_Country__c,
e.Event_Status__c, e.Publish_on_website__c, e.Title__c, e.Event_Summary__c, e.Event_Description__c
FROM Event__c e
WHERE e.Start_Date_time__c > TODAY
';

        $result = $this->salesForce->query($query);
        if ($result instanceof ApiProblemResponse) {
            throw new \RuntimeException($result->getContent());
        }

        return (array)$result;
    }
}