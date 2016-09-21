<?php

namespace Search\Controller;

use Search\Service\ElasticSearchService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

final class CountryController extends AbstractRestfulController
{
    /** @var ElasticSearchService */
    private $service;

    /**
     * CountryController constructor.
     *
     * @param ElasticSearchService $service
     */
    public function __construct(ElasticSearchService $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->service->getCountries();
    }
}
