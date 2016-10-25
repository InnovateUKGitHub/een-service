<?php

namespace Contact\Factory\Service;

use Common\Service\SalesForceService;
use Contact\Service\EoiService;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

final class EoiServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EoiService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $salesForce = $serviceManager->get(SalesForceService::class);
        $queryService = $serviceManager->get(QueryService::class);

        return new EoiService($salesForce, $queryService);
    }
}