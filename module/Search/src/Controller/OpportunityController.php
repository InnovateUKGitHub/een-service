<?php

namespace Search\Controller;

use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
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
    /** @var MerlinService */
    private $merlin;

    /**
     * OpportunitiesController constructor.
     *
     * @param ElasticSearchService $service
     * @param MerlinService        $merlin
     */
    public function __construct(ElasticSearchService $service, MerlinService $merlin)
    {
        $this->service = $service;
        $this->merlin = $merlin;
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
    public function listAction()
    {
        return new ViewModel($this->merlin->getOpportunities());
    }

    /**
     * @return ViewModel
     */
    public function detailAction()
    {
        $id = (string)$this->params()->fromRoute('id');

        return new ViewModel($this->merlin->getOpportunities($id));
    }
}
