<?php

use Console\Controller as Controller;
use Console\Factory\Controller as ControllerFactory;
use Console\Factory\Service as ServiceFactory;
use Console\Factory\Validator as ValidatorFactory;
use Console\Service as Service;
use Console\Validator as Validator;

return [
    'service_manager' => [
        'factories' => [
            Service\PurgeService::class    => ServiceFactory\PurgeServiceFactory::class,
            Service\GenerateService::class => ServiceFactory\GenerateServiceFactory::class,
            Service\HttpService::class     => ServiceFactory\HttpServiceFactory::class,
            Service\IndexService::class    => ServiceFactory\IndexServiceFactory::class,

            Service\Import\ImportService::class      => ServiceFactory\Import\ImportServiceFactory::class,
            Service\Import\DeleteService::class      => ServiceFactory\Import\DeleteServiceFactory::class,
            Service\Import\OpportunityService::class => ServiceFactory\Import\OpportunityServiceFactory::class,
            Service\Import\EventService::class       => ServiceFactory\Import\EventServiceFactory::class,
            Service\Import\Event\MerlinIngest::class => ServiceFactory\Import\Event\MerlinIngestFactory::class,
            Service\Import\Event\EventBrite::class   => ServiceFactory\Import\Event\EventBriteFactory::class,
            Service\Merlin\OpportunityMerlin::class  => ServiceFactory\Merlin\OpportunityMerlinFactory::class,
            Service\Merlin\EventMerlin::class        => ServiceFactory\Merlin\EventMerlinFactory::class,

            Validator\MerlinValidator::class => ValidatorFactory\MerlinValidatorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            Controller\GenerateController::class => ControllerFactory\GenerateControllerFactory::class,
            Controller\ImportController::class   => ControllerFactory\ImportControllerFactory::class,
        ],
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'import-data'   => [
                    'options' => [
                        'route'       => 'import [--index=<index>] [--month=<month>] [--type=<type>]',
                        'constraints' => [
                            'index' => '[opportunity|event]',
                            'month' => '[1|2|3|4|5|6|7|8|9|10|11|12]',
                            'type'  => '[s|u]',
                        ],
                        'defaults'    => [
                            'controller' => Controller\ImportController::class,
                            'action'     => 'import',
                        ],
                    ],
                ],
                'delete-data'   => [
                    'options' => [
                        'route'       => 'delete [--index=<index>] [--since=<since>]',
                        'constraints' => [
                            'index' => '[opportunity|event]',
                            'since' => '[\d]+',
                        ],
                        'defaults'    => [
                            'controller' => Controller\ImportController::class,
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
                            'controller' => Controller\GenerateController::class,
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
                            'controller' => Controller\GenerateController::class,
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
