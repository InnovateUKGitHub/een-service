<?php

namespace Contact\Service;

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
    public function createEntity(\stdClass $object, $type)
    {
        $object = new \SoapVar($object, SOAP_ENC_OBJECT, $type, $this->salesForce->getNamespace());
        $object = new \SoapParam([$object], 'sObjects');

        return $this->salesForce->create($object);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    abstract function create($data);
}
