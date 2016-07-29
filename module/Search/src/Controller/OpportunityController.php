<?php

namespace Search\Controller;

use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Zend\InputFilter\InputFilter;

/**
 * Class OpportunitiesController
 *
 * @package Search\Controller\Opportunities
 */
class OpportunitiesController extends AbstractActionController
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
    public function opportunitiesAction()
    {
        $params = $this->getParams();

        return new ViewModel($this->service->searchOpportunities($params));
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
