<?php

namespace Contact\Service;

class ContactService
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
     * @param array $account
     *
     * @return array
     */
    public function create($account)
    {
        $object = new \stdClass();
        $object->FirstName = $account['firstname'];
        $object->LastName = $account['lastname'];
        $object->Email = $account['email'];
        $object->Phone = $account['phone'];
        $object->MobilePhone = $account['mobile'];
        $object->Company = $account['company'];
        $object->CompanyNumber = $account['company-number'];
        $object->CompanyPostcode = $account['company-postcode'];
        $object->CompanyAddress = $account['company-address'];
        $object->CompanyPhone = $account['company-phone'];
        $object->CompanyWebsite = $account['company-website'];

        $object = new \SoapVar($object, SOAP_ENC_OBJECT, 'Contact', $this->salesForce->getNamespace());
        $object = new \SoapParam([$object], 'sObjects');

        return $this->salesForce->create($object);
    }
}
