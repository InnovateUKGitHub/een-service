<?php

use Console\Factory\Controller\GenerateControllerFactory;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Factory\Service\ConnectionServiceFactory;
use Console\Factory\Service\DeleteServiceFactory;
use Console\Factory\Service\GenerateServiceFactory;
use Console\Factory\Service\HttpServiceFactory;
use Console\Factory\Service\ImportServiceFactory;
use Console\Controller\GenerateController;
use Console\Controller\ImportController;
use Console\Service\GenerateService;
use Console\Service\DeleteService;
use Console\Service\ConnectionService;
use Console\Service\HttpService;
use Console\Service\ImportService;

return [
    'controllers'     => [
        'factories' => [
            GenerateController::class => GenerateControllerFactory::class,
            ImportController::class   => ImportControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ConnectionService::class => ConnectionServiceFactory::class,
            DeleteService::class     => DeleteServiceFactory::class,
            GenerateService::class   => GenerateServiceFactory::class,
            HttpService::class       => HttpServiceFactory::class,
            ImportService::class     => ImportServiceFactory::class,
        ],
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'import-data' => [
                    'options' => [
                        'route'       => 'import [--type=<type>]',
                        'constraints' => [
                            'type' => '[bo|all]',
                        ],
                        'defaults'    => [
                            'controller' => ImportController::class,
                            'action'     => 'import',
                        ],
                    ],
                ],
                'generate-data' => [
                    'options' => [
                        'route'       => 'generate [--index=<index>] [--number=<number>]',
                        'constraints' => [
                            'index' => '[opportunity|event|all]',
                        ],
                        'defaults'    => [
                            'controller' => GenerateController::class,
                            'action'     => 'generate',
                        ],
                    ],
                ],
                'delete-data'   => [
                    'options' => [
                        'route'       => 'delete [--index=<index>]',
                        'constraints' => [
                            'index' => '[opportunity|event|all]',
                        ],
                        'defaults'    => [
                            'controller' => GenerateController::class,
                            'action'     => 'delete',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
