<?php

namespace Search\Controller;

use Search\Service\EventsService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class EventsController extends AbstractRestfulController
{
    /** @var EventsService */
    private $service;

    /**
     * OpportunitiesController constructor.
     *
     * @param EventsService $service
     */
    public function __construct(EventsService $service)
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

        return $this->service->search($params);
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
