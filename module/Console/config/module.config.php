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
            Service\DeleteService::class   => ServiceFactory\DeleteServiceFactory::class,
            Service\GenerateService::class => ServiceFactory\GenerateServiceFactory::class,
            Service\IndexService::class    => ServiceFactory\IndexServiceFactory::class,
            Service\ImportService::class   => ServiceFactory\ImportServiceFactory::class,
            Service\PurgeService::class    => ServiceFactory\PurgeServiceFactory::class,

            Service\Event\EventBrite::class       => ServiceFactory\Event\EventBriteFactory::class,
            Service\Event\EventService::class     => ServiceFactory\Event\EventServiceFactory::class,
            Service\Event\MerlinConnection::class => ServiceFactory\Event\MerlinConnectionFactory::class,
            Service\Event\Merlin::class           => ServiceFactory\Event\MerlinFactory::class,
            Service\Event\SalesForce::class       => ServiceFactory\Event\SalesForceFactory::class,

            Service\Opportunity\OpportunityMerlin::class  => ServiceFactory\Opportunity\OpportunityMerlinFactory::class,
            Service\Opportunity\OpportunityService::class => ServiceFactory\Opportunity\OpportunityServiceFactory::class,

            Validator\MerlinValidator::class => ValidatorFactory\MerlinValidatorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            Controller\GenerateController::class => ControllerFactory\GenerateControllerFactory::class,
            Controller\ImportController::class   => ControllerFactory\ImportControllerFactory::class,
            Controller\IndexController::class    => ControllerFactory\IndexControllerFactory::class,
        ],
    ],
    'console'         => [
        'router'       => [
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
                        'route'       => 'delete [--index=<index>]',
                        'constraints' => [
                            'index' => '[opportunity|event]',
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
                'purge'    => [
                    'options' => [
                        'route'       => 'purge [--index=<index>]',
                        'constraints' => [
                            'index' => '[opportunity|event|all]',
                        ],
                        'defaults'    => [
                            'controller' => Controller\GenerateController::class,
                            'action'     => 'purge',
                        ],
                    ],
                ],
                'index'         => [
                    'options' => [
                        'route'    => 'index',
                        'defaults' => [
                            'controller' => Controller\IndexController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],
        'view_manager' => [
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
        ],
    ],
];
