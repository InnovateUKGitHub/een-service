<?php

namespace Search;

use Search\Controller\CountryController;
use Search\Controller\EventsController;
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\EventsControllerFactory;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Factory\Service\QueryServiceFactory;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            ElasticSearchService::class => ElasticSearchServiceFactory::class,
            QueryService::class         => QueryServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            OpportunitiesController::class => OpportunitiesControllerFactory::class,
            EventsController::class        => EventsControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
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
            'een.events'        => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/events[/:id]',
                    'constraints' => [
                        'id' => '[\d]+\.[\d]+',
                    ],
                    'defaults'    => [
                        'controller' => EventsController::class,
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
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            OpportunitiesController::class => 'Json',
            EventsController::class        => 'Json',
        ],
        'accept_whitelist'       => [
            OpportunitiesController::class => [
                'application/json',
                'application/*+json',
            ],
            EventsController::class        => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            OpportunitiesController::class => [
                'application/json',
            ],
            EventsController::class        => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        OpportunitiesController::class => [
            'POST' => OpportunitiesController::class,
        ],
        EventsController::class        => [
            'POST' => EventsController::class,
        ],
    ],
    'input_filter_specs'     => [
        OpportunitiesController::class => [
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
                'name'       => 'sort',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
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
                'name'       => 'sort',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
            ],
        ],
    ],
    'view_manager'           => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
