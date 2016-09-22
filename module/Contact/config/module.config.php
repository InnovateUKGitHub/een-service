<?php

namespace Contact;

use Contact\Controller\ContactController;
use Contact\Factory\Controller\ContactControllerFactory;
use Contact\Factory\Service\ContactServiceFactory;
use Contact\Factory\Service\SalesForceServiceFactory;
use Contact\Service\ContactService;
use Contact\Service\SalesForceService;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            ContactService::class    => ContactServiceFactory::class,
            SalesForceService::class => SalesForceServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            ContactController::class => ContactControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
            'een.contact' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/contact[/:id]',
                    'constraints' => [
                        'id' => '[\d]+',
                    ],
                    'defaults'    => [
                        'controller' => ContactController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            ContactController::class => 'Json',
        ],
        'accept_whitelist'       => [
            ContactController::class => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            ContactController::class => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        ContactController::class => [
            'POST' => ContactController::class,
        ],
    ],
    'input_filter_specs'     => [
        ContactController::class => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'name',
            ],
        ],
    ],
    'view_manager'           => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
