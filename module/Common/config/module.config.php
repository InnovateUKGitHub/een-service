<?php

use Common\Factory\HttpServiceFactory;
use Common\Factory\SalesForceServiceFactory;
use Common\Service\HttpService;
use Common\Service\SalesForceService;

return [
    'service_manager' => [
        'factories' => [
            HttpService::class => HttpServiceFactory::class,
            SalesForceService::class => SalesForceServiceFactory::class,
        ],
    ],
];
