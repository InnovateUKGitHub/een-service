<?php

namespace Sync\Factory\Service;

use Common\Service\SalesForceService;
use Search\Service\QueryService;
use Sync\Service\SavedSearchesService;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Renderer\PhpRenderer;

final class SavedSearchesServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return SavedSearchesService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $query = $serviceManager->get(QueryService::class);
        $salesForce = $serviceManager->get(SalesForceService::class);
        $renderer = $serviceManager->get(PhpRenderer::class);

        return new SavedSearchesService($query, $salesForce, $renderer);
    }
}
