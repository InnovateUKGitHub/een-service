<?php

namespace Search\V1\Rpc\Opportunities;

use Search\V1\ElasticSearch\Service\ElasticSearchService;
use Search\V1\Merlin\Service\MerlinService;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class OpportunitiesController
 *
 * @package Search\V1\Rpc\Opportunities
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
        $inputFilter = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter');

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
