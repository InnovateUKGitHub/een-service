<?php

namespace Search\Controller;

use Search\Service\ElasticSearchService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class OpportunitiesController extends AbstractRestfulController
{
    /** @var ElasticSearchService */
    private $service;

    /**
     * OpportunitiesController constructor.
     *
     * @param ElasticSearchService $service
     */
    public function __construct(ElasticSearchService $service)
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

        return $this->service->searchOpportunities($params);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function get($id)
    {
        return $this->service->getOpportunity($id);
    }
}
