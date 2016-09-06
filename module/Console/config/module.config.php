<?php

use Console\Controller\GenerateController;
use Console\Controller\ImportController;
use Console\Factory\Controller\GenerateControllerFactory;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Factory\Service\DeleteServiceFactory;
use Console\Factory\Service\GenerateServiceFactory;
use Console\Factory\Service\HttpServiceFactory;
use Console\Factory\Service\ImportServiceFactory;
use Console\Factory\Service\IndexServiceFactory;
use Console\Factory\Validator\MerlinValidatorFactory;
use Console\Service\DeleteService;
use Console\Service\GenerateService;
use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;

return [
    'service_manager' => [
        'factories' => [
            DeleteService::class   => DeleteServiceFactory::class,
            GenerateService::class => GenerateServiceFactory::class,
            HttpService::class     => HttpServiceFactory::class,
            ImportService::class   => ImportServiceFactory::class,
            IndexService::class    => IndexServiceFactory::class,
            MerlinValidator::class => MerlinValidatorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            GenerateController::class => GenerateControllerFactory::class,
            ImportController::class   => ImportControllerFactory::class,
        ],
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'import-data'   => [
                    'options' => [
                        'route'       => 'import [--month=<month>] [--type=<type>]',
                        'constraints' => [
                            'month' => '[1|2|3|4|5|6|7|8|9|10|11|12]',
                            'type'  => '[s|u]',
                        ],
                        'defaults'    => [
                            'controller' => ImportController::class,
                            'action'     => 'import',
                        ],
                    ],
                ],
                'delete-data'   => [
                    'options' => [
                        'route'       => 'delete [--since=<since>]',
                        'constraints' => [
                            'since' => '[\d]+',
                        ],
                        'defaults'    => [
                            'controller' => ImportController::class,
                            'action'     => 'delete',
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
                'delete-all'    => [
                    'options' => [
                        'route'       => 'delete-all [--index=<index>]',
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
    'view_manager'    => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
];
