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

        if (isset($contact) && $contact['size'] !== 0) {
            return $contact;
        }

        $lead = new \stdClass();
        $lead->Email1__c = $data['email'];
        $lead->LastName = 'Lead';

        $this->createEntity($lead, 'Contact');

        return $this->getContact($data['email']);
    }
}
