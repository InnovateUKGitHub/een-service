<?php

namespace Contact\Controller;

use Contact\Service\LeadService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class LeadController extends AbstractRestfulController
{
    /** @var LeadService */
    private $service;

    /**
     * LeadController constructor.
     *
     * @param LeadService $service
     */
    public function __construct(LeadService $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $params = $this->getInputFilter()->getValues();

        return $this->service->create($params);
    }
}
