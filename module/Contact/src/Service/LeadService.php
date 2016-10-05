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

    private function isContactExists($email)
    {
        $query = new \stdClass();
        $query->queryString = 'SELECT Id, Email, Contact_Status__c FROM Contact WHERE Email = \'' . $email . '\'';

        $result = $this->salesForce->query($query);
        if ($result instanceof ApiProblemResponse) {
            return $result;
        }

        if ($result->size == 0) {
            return false;
        }

        return [
            'id'    => $result->records->Id,
            'email' => $result->records->Email,
            'type'  => $result->records->Contact_Status__c,
        ];
    }
}
