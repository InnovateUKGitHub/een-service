<?php

namespace Contact;

use Contact\Controller\ContactController;
use Contact\Controller\DescribeController;
use Contact\Controller\EmailController;
use Contact\Controller\LeadController;
use Contact\Factory\Controller\ContactControllerFactory;
use Contact\Factory\Controller\DescribeControllerFactory;
use Contact\Factory\Controller\EmailControllerFactory;
use Contact\Factory\Controller\LeadControllerFactory;
use Contact\Factory\Service\ContactServiceFactory;
use Contact\Factory\Service\LeadServiceFactory;
use Contact\Factory\Service\SalesForceServiceFactory;
use Contact\Service\ContactService;
use Contact\Service\LeadService;
use Contact\Service\SalesForceService;
use Zend\Router\Http\Segment;
use Zend\Validator\EmailAddress;

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
            ContactController::class  => ContactControllerFactory::class,
            DescribeController::class => DescribeControllerFactory::class,
            LeadController::class     => LeadControllerFactory::class,
            EmailController::class    => EmailControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
            'een.describe'           => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/describe[/:id]',
                    'constraints' => [
                        'id' => '[a-zA-Z]+',
                    ],
                    'defaults'    => [
                        'controller' => DescribeController::class,
                    ],
                ],
            ],
            'een.contact'            => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/contact[/:id]',
                    'constraints' => [
                        'id' => '[\w\d_\-\.@]+',
                    ],
                    'defaults'    => [
                        'controller' => ContactController::class,
                    ],
                ],
            ],
            'een.lead'               => [
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
            'een.email.verification' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/email-verification',
                    'defaults' => [
                        'controller' => EmailController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            DescribeController::class => 'Json',
            ContactController::class  => 'Json',
            LeadController::class     => 'Json',
            EmailController::class    => 'Json',
        ],
        'accept_whitelist'       => [
            DescribeController::class => [
                'application/json',
                'application/*+json',
            ],
            ContactController::class  => [
                'application/json',
                'application/*+json',
            ],
            LeadController::class     => [
                'application/json',
                'application/*+json',
            ],
            EmailController::class    => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            DescribeController::class => [
                'application/json',
            ],
            ContactController::class  => [
                'application/json',
            ],
            LeadController::class     => [
                'application/json',
            ],
            EmailController::class    => [
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
        EmailController::class   => [
            'POST' => EmailController::class,
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
        ],
        ContactController::class => [
            [
                'required'   => true,
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                    ],
                ],
                'filters'    => [],
                'name'       => 'other_email',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'description',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'interest',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'more',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'phone',
            ],
            [
                'required'   => true,
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                    ],
                ],
                'filters'    => [],
                'name'       => 'email',
            ],

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
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                    ],
                ],
                'filters'    => [],
                'name'       => 'contact_email',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'contact_phone',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'newsletter',
            ],

            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company_name',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company_number',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'website',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company_phone',
            ],

            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'postcode',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'addressone',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'addresstwo',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'city',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'county',
            ],
        ],
        EmailController::class   => [
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
                'name'       => 'url',
            ],
        ],
    ],
];
