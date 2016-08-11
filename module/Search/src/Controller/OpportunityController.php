<?php

namespace Search\Controller;

use Search\Service\ElasticSearchService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;

/**
 * @method InputFilter getInputFilter()
 */
final class OpportunitiesController extends AbstractActionController
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
     * @return ViewModel
     */
    public function opportunitiesAction()
    {
        $params = $this->getInputFilter()->getValues();

        return new ViewModel($this->service->searchOpportunities($params));
    }

    /**
     * @return ViewModel
     */
    public function detailAction()
    {
        $id = (string)$this->params()->fromRoute('id');

        return new ViewModel($this->service->searchOpportunity($id));
    }
}
