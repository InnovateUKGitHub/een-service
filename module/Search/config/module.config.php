<?php
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Factory\Service\MerlinServiceFactory;
use Search\Factory\Service\QueryServiceFactory;
use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
use Search\Service\QueryService;

return [
    'service_manager'        => [
        'factories' => [
            MerlinService::class        => MerlinServiceFactory::class,
            ElasticSearchService::class => ElasticSearchServiceFactory::class,
            QueryService::class         => QueryServiceFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
            'een.opportunities' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/v1/een/opportunities',
                    'defaults' => [
                        'controller' => OpportunitiesController::class,
                        'action'     => 'opportunities',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'details' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:id',
                            'constraints' => [
                                'id' => '[\d\w]+',
                            ],
                            'defaults'    => [
                                'action' => 'detail',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning'          => [
        'uri' => [
            0 => 'een.opportunities',
        ],
    ],
    'zf-rest'                => [],
    'zf-content-negotiation' => [
        'controllers'            => [
            OpportunitiesController::class => 'Json',
        ],
        'accept_whitelist'       => [
            OpportunitiesController::class => [
                0 => 'application/vnd.een.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            OpportunitiesController::class => [
                0 => 'application/vnd.een.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal'                 => [
        'metadata_map' => [],
    ],
    'zf-content-validation'  => [
        OpportunitiesController::class => [
            'input_filter' => 'Search\\Opportunities\\Validator',
        ],
    ],
    'input_filter_specs'     => [
        'Search\\Opportunities\\Validator' => [
            0 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'from',
            ],
            1 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'size',
            ],
            2 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'search',
            ],
            3 => [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'opportunity_type',
            ],
            4 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'sort',
            ],
            5 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
            ],
        ],
    ],
    'controllers'            => [
        'factories' => [
            OpportunitiesController::class => OpportunitiesControllerFactory::class,
        ],
    ],
    'zf-rpc'                 => [
        OpportunitiesController::class => [
            'service_name' => 'Opportunities',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name'   => 'een.opportunities',
        ],
    ],
];
