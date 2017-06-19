<?php

namespace Search;

use Search\Controller\AutoSuggestController;
use Search\Controller\CountryController;
use Search\Controller\EventsController;
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\AutoSuggestControllerFactory;
use Search\Factory\Controller\CountryControllerFactory;
use Search\Factory\Controller\EventsControllerFactory;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Factory\Service\EventsServiceFactory;
use Search\Factory\Service\OpportunitiesServiceFactory;
use Search\Factory\Service\QueryServiceFactory;
use Search\Service\EventsService;
use Search\Service\OpportunitiesService;
use Search\Service\QueryService;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            EventsService::class        => EventsServiceFactory::class,
            OpportunitiesService::class => OpportunitiesServiceFactory::class,
            QueryService::class         => QueryServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            AutoSuggestController::class   => AutoSuggestControllerFactory::class,
            CountryController::class       => CountryControllerFactory::class,
            OpportunitiesController::class => OpportunitiesControllerFactory::class,
            EventsController::class        => EventsControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
            'een.auto.suggest'  => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/auto-suggest',
                    'defaults' => [
                        'controller' => AutoSuggestController::class,
                    ],
                ],
            ],
            'een.countries'     => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/countries',
                    'defaults' => [
                        'controller' => CountryController::class,
                    ],
                ],
            ],
            'een.events'        => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/events[/:id]',
                    'constraints' => [
                        'id' => '[\w]+',
                    ],
                    'defaults'    => [
                        'controller' => EventsController::class,
                    ],
                ],
            ],
            'een.opportunities' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/opportunities[/:id]',
                    'constraints' => [
                        'id' => '[\d\w]+',
                    ],
                    'defaults'    => [
                        'controller' => OpportunitiesController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            AutoSuggestController::class   => 'Json',
            CountryController::class       => 'Json',
            EventsController::class        => 'Json',
            OpportunitiesController::class => 'Json',
        ],
        'accept_whitelist'       => [
            AutoSuggestController::class   => [
                'application/json',
                'application/*+json',
            ],
            CountryController::class       => [
                'application/json',
                'application/*+json',
            ],
            EventsController::class        => [
                'application/json',
                'application/*+json',
            ],
            OpportunitiesController::class => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            AutoSuggestController::class   => [
                'application/json',
            ],
            CountryController::class       => [
                'application/json',
            ],
            EventsController::class        => [
                'application/json',
            ],
            OpportunitiesController::class => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        AutoSuggestController::class   => [
            'POST' => AutoSuggestController::class,
        ],
        EventsController::class        => [
            'POST' => EventsController::class,
        ],
        OpportunitiesController::class => [
            'POST' => OpportunitiesController::class,
        ],
    ],
    'input_filter_specs'     => [
        AutoSuggestController::class   => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'search',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'size',
            ],
        ],
        EventsController::class        => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'from',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'size',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'search',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'date_type',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'date_from',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'date_to',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'country',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'sort',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
            ],
        ],
        OpportunitiesController::class => [
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'from',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'size',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'type',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'search',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'opportunity_type',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'country',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'sort',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'count',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'aggregations',
            ],
        ],
    ],
];
