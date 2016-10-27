<?php

namespace Sync\Factory\Controller;

use Sync\Controller\SavedSearchesController;
use Sync\Service\SavedSearchesService;
use Zend\ServiceManager\ServiceManager;

final class SavedSearchesControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return SavedSearchesController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $savedSearchesService = $serviceManager->get(SavedSearchesService::class);

        return new SavedSearchesController($savedSearchesService);
    }
}
