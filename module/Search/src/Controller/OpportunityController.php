<?php

namespace Search\Controller;

use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;

final class OpportunitiesController extends AbstractActionController
{
    /** @var ElasticSearchService */
    private $service;
    /** @var MerlinService */
    private $merlin;

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
        $params = $this->getParams();

        return new ViewModel($this->service->searchOpportunities($params));
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        $inputFilter = $this->getEvent()->getParam(InputFilter::class);

        return $inputFilter->getValues();
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
