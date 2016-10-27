<?php

namespace Search\Controller;

use Search\Service\QueryService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * TODO This controller is WIP to build an auto suggest
 *
 * @method InputFilter getInputFilter()
 */
final class AutoSuggestController extends AbstractRestfulController
{
    /** @var QueryService */
    private $service;

    /**
     * CountryController constructor.
     *
     * @param QueryService $service
     */
    public function __construct(QueryService $service)
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

        return $this->service->findTerm($params['search'], $params['size']);
    }
}
