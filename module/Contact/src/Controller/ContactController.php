<?php

namespace Contact\Controller;

use Contact\Service\ContactService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class ContactController extends AbstractRestfulController
{
    /** @var ContactService */
    private $service;

    /**
     * OpportunitiesController constructor.
     *
     * @param ContactService $service
     */
    public function __construct(ContactService $service)
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

    /**
     * @param string $id
     *
     * @return array
     */
    public function get($id)
    {
        return $this->service->get($id);
    }
}
