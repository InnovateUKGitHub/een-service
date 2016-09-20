<?php

namespace Contact\Factory\Service;

use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;
use Zend\Soap\Client;

final class ContactServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return ContactService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $soap = new Client(__DIR__ . '/../../../../../config/SF_EEN_Enterprise.wsdl');

        return new ContactService($soap);
    }
}