<?php

namespace Search\V1\Rpc\Opportunities;

use Search\V1\ElasticSearch\Service\ElasticSearchService;
use Search\V1\Merlin\Service\MerlinService;
use Zend\Mvc\Controller\ControllerManager;

/**
 * Class OpportunitiesControllerFactory
 *
 * @package Search\V1\Rpc\Opportunities
 */
class OpportunitiesControllerFactory
{
    /**
     * @param ControllerManager $controllers
     *
     * @return OpportunitiesController
     */
    public function __invoke(ControllerManager $controllers)
    {
        $serviceLocator = $controllers->getServiceLocator();
        $service = $serviceLocator->get(ElasticSearchService::class);
        $merlin = $serviceLocator->get(MerlinService::class);

        return new OpportunitiesController($service, $merlin);
    }
}
