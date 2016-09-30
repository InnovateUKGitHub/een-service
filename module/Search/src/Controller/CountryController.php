<?php

namespace Search\Controller;

use Search\Service\QueryService;
use Zend\Mvc\Controller\AbstractRestfulController;

final class CountryController extends AbstractRestfulController
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
     * @return array
     */
    public function getList()
    {
        return $this->service->getCountryList();
    }
}
