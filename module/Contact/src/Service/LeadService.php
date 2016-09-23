<?php

namespace Contact\Service;

class LeadService extends AbstractEntity
{
    /**
     * @param array $lead
     *
     * @return array
     */
    public function create($lead)
    {
        $lead = new \stdClass();
        $lead->Email = $lead['email'];

        if (isset($lead['lastname'])) {
            $lead->LastName = $lead['lastname'];
        }
        if (isset($lead['company'])) {
            $lead->Company = $lead['company'];
        }

        return $this->createEntity($lead, 'Lead');
    }
}
