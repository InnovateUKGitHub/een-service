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
        $isContact = $this->isContactExists($data['email']);
        if ($isContact !== false) {
            return $isContact;
        }

        $lead = new \stdClass();
        $lead->Email = $data['email'];
        $lead->Email1__c = $data['email'];

        if (isset($data['lastname'])) {
            $lead->LastName = $data['lastname'];
        } else {
            $lead->LastName = 'Lead';
        }

        $result = $this->createEntity($lead, 'Contact');
        if ($result instanceof ApiProblemResponse) {
            return $result;
        }
        return $this->isContactExists($data['email']);
    }
}
