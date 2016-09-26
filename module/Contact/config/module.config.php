<?php

namespace Contact;

use Contact\Controller\ContactController;
use Contact\Controller\LeadController;
use Contact\Factory\Controller\ContactControllerFactory;
use Contact\Factory\Controller\LeadControllerFactory;
use Contact\Factory\Service\ContactServiceFactory;
use Contact\Factory\Service\LeadServiceFactory;
use Contact\Factory\Service\SalesForceServiceFactory;
use Contact\Service\ContactService;
use Contact\Service\LeadService;
use Contact\Service\SalesForceService;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            ContactService::class    => ContactServiceFactory::class,
            LeadService::class       => LeadServiceFactory::class,
            SalesForceService::class => SalesForceServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            ContactController::class => ContactControllerFactory::class,
            LeadController::class    => LeadControllerFactory::class,
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
            'een.lead'    => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/lead[/:id]',
                    'constraints' => [
                        'id' => '[\d]+',
                    ],
                    'defaults'    => [
                        'controller' => LeadController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            ContactController::class => 'Json',
            LeadController::class    => 'Json',
        ],
        'accept_whitelist'       => [
            ContactController::class => [
                'application/json',
                'application/*+json',
            ],
            LeadController::class    => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            ContactController::class => [
                'application/json',
            ],
            LeadController::class    => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        ContactController::class => [
            'POST' => ContactController::class,
        ],
        LeadController::class    => [
            'POST' => LeadController::class,
        ],
    ],
    'input_filter_specs'     => [
        LeadController::class    => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'email',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'lastname',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company',
            ],
        ],
        ContactController::class => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'firstname',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'lastname',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'email',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'phone',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'mobile',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-name',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-phone',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-number',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-address',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-postcode',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-city',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company-country',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'website',
            ],
        ],
    ],
    'view_manager'           => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
