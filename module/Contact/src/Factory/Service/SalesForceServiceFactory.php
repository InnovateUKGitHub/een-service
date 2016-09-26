<?php

namespace Contact\Factory\Service;

use Contact\Service\SalesForceService;
use Zend\ServiceManager\ServiceManager;
use Zend\Soap\Client;

final class SalesForceServiceFactory
{
    const CONFIG = 'config';
    const SALES_FORCE = 'sales-force';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return SalesForceService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(self::CONFIG);

        $soap = new Client(
            __DIR__ . '/../../../../../config/SF_EEN_Enterprise.wsdl',
            [
                'soap_version' => SOAP_1_1,
            ]
        );

        return new SalesForceService($soap, $config[self::SALES_FORCE]);
    }
}