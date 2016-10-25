<?php

namespace Contact\Service;

use ZF\ApiProblem\ApiProblemResponse;

class LeadService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array|ApiProblemResponse
     */
    public function create($data)
    {
        $contact = $this->getContact($data['email']);

        if ($contact !== null) {
            return (array)$contact;
        }

        $lead = new \stdClass();
        $lead->Email1__c = $data['email'];
        $lead->LastName = 'Lead';

        $result = $this->createEntity($lead, 'Contact');
        if ($result instanceof ApiProblemResponse) {
            return $result;
        }

        return (array)$this->getContact($data['email']);
    }
}
