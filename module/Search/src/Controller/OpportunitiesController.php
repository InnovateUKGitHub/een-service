<?php

namespace Search\Controller;

use Search\Service\OpportunitiesService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class OpportunitiesController extends AbstractRestfulController
{
    /** @var OpportunitiesService */
    private $service;

    /**
     * OpportunitiesController constructor.
     *
     * @param OpportunitiesService $service
     */
    public function __construct(OpportunitiesService $service)
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

        if (array_key_exists('count', $params) === true && $params['count'] === true) {
            return ['total' => $this->service->count($params)['count']];
        }

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
