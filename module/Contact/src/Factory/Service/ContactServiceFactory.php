<?php

namespace Contact\Factory\Service;

use Console\Service\HttpService;
use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;

final class ContactServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return ContactService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $query = $serviceManager->get(HttpService::class);

        return new ContactService($query);
    }
}