<?php
use Search\V1\ElasticSearch\Service\ElasticSearchService;
use Search\V1\ElasticSearch\Factory\ElasticSearchServiceFactory;
use Search\V1\ElasticSearch\Service\QueryService;
use Search\V1\ElasticSearch\Factory\QueryServiceFactory;
use Search\V1\Rpc\Opportunities\OpportunitiesController;
use Search\V1\Rpc\Opportunities\OpportunitiesControllerFactory;
use Search\V1\Merlin\Service\MerlinService;
use Search\V1\Merlin\Factory\MerlinServiceFactory;

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
            'een.rpc.opportunities' => [
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
                            'route'       => '/details/:id',
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
            'een.rpc.event'       => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/v1/een/event',
                    'defaults' => [
                        'controller' => 'Search\\V1\\Rpc\\Event\\Controller',
                        'action'     => 'event',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning'          => [
        'uri' => [
            0 => 'een.rpc.opportunities',
            1 => 'een.rpc.event',
        ],
    ],
    'zf-rest'                => [],
    'zf-content-negotiation' => [
        'controllers'            => [
            OpportunitiesController::class         => 'Json',
            'Search\\V1\\Rpc\\Event\\Controller' => 'Json',
        ],
        'accept_whitelist'       => [
            OpportunitiesController::class         => [
                0 => 'application/vnd.een.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Search\\V1\\Rpc\\Event\\Controller' => [
                0 => 'application/vnd.een.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            OpportunitiesController::class         => [
                0 => 'application/vnd.een.v1+json',
                1 => 'application/json',
            ],
            'Search\\V1\\Rpc\\Event\\Controller' => [
                0 => 'application/vnd.een.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal'                 => [
        'metadata_map' => [],
    ],
    'zf-content-validation'  => [
        OpportunitiesController::class         => [
            'input_filter' => 'Search\\V1\\Rpc\\Opportunities\\Validator',
        ],
        'Search\\V1\\Rpc\\Event\\Controller' => [
            'input_filter' => 'Search\\V1\\Rpc\\Event\\Validator',
        ],
    ],
    'input_filter_specs'     => [
        'Search\\V1\\Rpc\\Opportunities\\Validator' => [
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
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'sort',
            ],
            4 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
            ],
        ],
        'Search\\V1\\Rpc\\Event\\Validator'       => [
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
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'sort',
            ],
            4 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'source',
            ],
        ],
    ],
    'controllers'            => [
        'factories' => [
            OpportunitiesController::class         => OpportunitiesControllerFactory::class,
            'Search\\V1\\Rpc\\Event\\Controller' => 'Search\\V1\\Rpc\\Event\\EventControllerFactory',
        ],
    ],
    'zf-rpc'                 => [
        OpportunitiesController::class         => [
            'service_name' => 'Opportunities',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name'   => 'een.rpc.opportunities',
        ],
        'Search\\V1\\Rpc\\Event\\Controller' => [
            'service_name' => 'Event',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name'   => 'een.rpc.event',
        ],
    ],
];
