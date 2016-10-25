<?php

namespace Contact\Service;

use Common\Service\SalesForceService;

abstract class AbstractEntity
{
    /** @var SalesForceService */
    protected $salesForce;

    /**
     * @param SalesForceService $salesForce
     */
    public function __construct(SalesForceService $salesForce)
    {
        $this->salesForce = $salesForce;
    }

    /**
     * @param \stdClass $object
     * @param string    $type
     *
     * @return array
     */
    protected function createEntity(\stdClass $object, $type)
    {
        $object = new \SoapVar($object, SOAP_ENC_OBJECT, $type, $this->salesForce->getNamespace());
        $object = new \SoapParam([$object], 'sObjects');

        return $this->salesForce->action($object, 'create');
    }

    /**
     * @param \stdClass $object
     * @param string    $type
     *
     * @return array
     */
    protected function updateEntity(\stdClass $object, $type)
    {
        $object = new \SoapVar($object, SOAP_ENC_OBJECT, $type, $this->salesForce->getNamespace());
        $object = new \SoapParam([$object], 'sObjects');

        return $this->salesForce->action($object, 'update');
    }

    /**
     * @param string $email
     *
     * @return array|\ZF\ApiProblem\ApiProblemResponse
     */
    public function getContact($email)
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT c.Id, c.Email, c.Contact_Status__c, c.FirstName, c.LastName, c.Phone, c.MobilePhone,
c.Email_Newsletter__c, c.MailingStreet, c.MailingPostalCode, c.MailingCity,
a.Id, a.Name, a.Phone, a.Website, a.Company_Registration_Number__c
FROM Contact c, c.Account a
WHERE Email1__c = \'' . $email . '\'
';

        $result = $this->salesForce->query($query);
        if (isset($result->records)) {
            return $result->records;
        }

        return null;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    abstract function create($data);
}
