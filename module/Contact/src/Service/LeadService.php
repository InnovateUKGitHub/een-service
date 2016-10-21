<?php

namespace Contact\Service;

use ZF\ApiProblem\ApiProblemResponse;

class LeadService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $contact = $this->getContact($data['email']);
        if (isset($contact->size) && $contact->size !== 0) {
            return $contact;
        }

        $lead = new \stdClass();
        $lead->Email1__c = $data['email'];
        $lead->LastName = 'Lead';

        $result = $this->createEntity($lead, 'Contact');
        if ($result instanceof ApiProblemResponse) {
            return $result;
        }
        return $this->getContact($data['email']);
    }
}
