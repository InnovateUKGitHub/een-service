<?php

namespace Contact\Service;

class LeadService
{
    /** @var SalesForceService */
    private $salesForce;

    /**
     * SalesForceService constructor.
     *
     * @param SalesForceService $salesForce
     */
    public function __construct(SalesForceService $salesForce)
    {
        $this->salesForce = $salesForce;
    }

    /**
     * @param array $lead
     *
     * @return array
     */
    public function create($lead)
    {
        $newLead = new \stdClass();
        $newLead->Email = $lead['email'];

        if (isset($lead['lastname'])) {
            $newLead->LastName = $lead['lastname'];
        }
        if (isset($lead['company'])) {
            $newLead->Company = $lead['company'];
        }

        $sObject = new \SoapVar($newLead, SOAP_ENC_OBJECT, 'Lead', $this->salesForce->getNamespace());
        $sObject = new \SoapParam([$sObject], 'sObjects');

        return $this->salesForce->create($sObject);
    }
}
