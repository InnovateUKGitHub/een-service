<?php

namespace Search;

use Contact\Service\ContactService;
use Contact\Factory\Service\ContactServiceFactory;
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            ContactService::class => ContactServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            OpportunitiesController::class => OpportunitiesControllerFactory::class,
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
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            OpportunitiesController::class => 'Json',
        ],
        'accept_whitelist'       => [
            OpportunitiesController::class => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            OpportunitiesController::class => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        OpportunitiesController::class => [
            'POST' => OpportunitiesController::class,
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
    ],
    'view_manager'           => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
