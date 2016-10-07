<?php

namespace Contact\Service;

use ZF\ApiProblem\ApiProblemResponse;

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
     * @return array|bool|\ZF\ApiProblem\ApiProblemResponse
     */
    protected function isContactExists($email)
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

    /**
     * @param array $data
     *
     * @return array
     */
    abstract function create($data);
}
