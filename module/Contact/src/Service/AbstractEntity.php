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
     * @return \SoapVar
     */
    public function createObject(\stdClass $object, $type)
    {
        return new \SoapVar($object, SOAP_ENC_OBJECT, $type, $this->salesForce->getNamespace());
    }

    /**
     * @param \stdClass[] $objects
     *
     * @return array
     */
    public function createEntities($objects)
    {
        $object = new \SoapParam($objects, 'sObjects');

        return $this->salesForce->create($object);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    abstract function create($data);
}
