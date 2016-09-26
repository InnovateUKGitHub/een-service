<?php

namespace Contact\Service;

class LeadService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $lead = new \stdClass();
        $lead->Email = $data['email'];

        if (isset($data['lastname'])) {
            $lead->LastName = $data['lastname'];
        }

        return $this->createEntity($lead, 'Contact');
    }
}
