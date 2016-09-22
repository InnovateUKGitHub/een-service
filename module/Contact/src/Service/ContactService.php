<?php

namespace Contact\Service;

class ContactService
{
    /** @var SalesForceService */
    private $salesForce;

    /**
     * MailService constructor.
     *
     * @param SalesForceService $salesForce
     */
    public function __construct(SalesForceService $salesForce)
    {
        $this->salesForce = $salesForce;
    }

    public function create($params)
    {
        return $this->salesForce->getUserInfo();
    }

    public function get($id)
    {
        return $id;
    }
}
