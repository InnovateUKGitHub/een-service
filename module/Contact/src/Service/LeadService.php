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
        $isContact = $this->getContact($data['email']);
        if ($isContact['size'] !== 0) {
            return $isContact;
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
