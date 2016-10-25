<?php

namespace Contact\Controller;

use Contact\Service\EventService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class EventController extends AbstractRestfulController
{
    /** @var EventService */
    private $service;

    /**
     * EventController constructor.
     *
     * @param EventService $service
     */
    public function __construct(EventService $service)
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
