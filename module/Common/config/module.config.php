<?php

use Common\Factory\HttpServiceFactory;
use Common\Service\HttpService;

return [
    'service_manager' => [
        'factories' => [
            HttpService::class => HttpServiceFactory::class,
        ],
    ],
];
