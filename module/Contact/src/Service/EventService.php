<?php

namespace Contact\Service;

use ZF\ApiProblem\ApiProblemResponse;

class EventService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {

        $attendee = new \stdClass();
        $attendee->Contact__c = $data['contact'];
        $attendee->Event__c = $data['event'];
        $attendee->Special_Dietary_Access_Requirements__c = $data['dietary'];

        if (($result = $this->createEntity($attendee, 'Attendee__c')) instanceof ApiProblemResponse) {
            return $result;
        }

        return ['id' => $result];
    }
}
