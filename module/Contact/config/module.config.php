<?php

namespace Contact;

use Contact\Controller\ContactController;
use Contact\Controller\DescribeController;
use Contact\Controller\EmailController;
use Contact\Controller\EoiController;
use Contact\Controller\EventController;
use Contact\Controller\LeadController;
use Contact\Factory\Controller\ContactControllerFactory;
use Contact\Factory\Controller\DescribeControllerFactory;
use Contact\Factory\Controller\EmailControllerFactory;
use Contact\Factory\Controller\EoiControllerFactory;
use Contact\Factory\Controller\EventControllerFactory;
use Contact\Factory\Controller\LeadControllerFactory;
use Contact\Factory\Service\ContactServiceFactory;
use Contact\Factory\Service\EoiServiceFactory;
use Contact\Factory\Service\EventServiceFactory;
use Contact\Factory\Service\LeadServiceFactory;
use Contact\Service\ContactService;
use Contact\Service\EoiService;
use Contact\Service\EventService;
use Contact\Service\LeadService;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Validator\EmailAddress;

return [
    'service_manager'        => [
        'factories' => [
            ContactService::class => ContactServiceFactory::class,
            EoiService::class     => EoiServiceFactory::class,
            EventService::class   => EventServiceFactory::class,
            LeadService::class    => LeadServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            ContactController::class  => ContactControllerFactory::class,
            DescribeController::class => DescribeControllerFactory::class,
            EmailController::class    => EmailControllerFactory::class,
            EoiController::class      => EoiControllerFactory::class,
            EventController::class    => EventControllerFactory::class,
            LeadController::class     => LeadControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
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
            'een.describe'           => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/describe[/:id]',
                    'constraints' => [
                        'id' => '[a-zA-Z\_]+',
                    ],
                    'defaults'    => [
                        'controller' => DescribeController::class,
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
            'een.eoi'                => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/eoi[/:id]',
                    'constraints' => [
                        'id' => '[\w\d]+',
                    ],
                    'defaults'    => [
                        'controller' => EoiController::class,
                    ],
                ],
            ],
            'een.contact.event'      => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/contact/event',
                    'defaults' => [
                        'controller' => EventController::class,
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
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            ContactController::class  => 'Json',
            DescribeController::class => 'Json',
            EmailController::class    => 'Json',
            EoiController::class      => 'Json',
            EventController::class    => 'Json',
            LeadController::class     => 'Json',
        ],
        'accept_whitelist'       => [
            ContactController::class  => [
                'application/json',
                'application/*+json',
            ],
            DescribeController::class => [
                'application/json',
                'application/*+json',
            ],
            EmailController::class    => [
                'application/json',
                'application/*+json',
            ],
            EoiController::class      => [
                'application/json',
                'application/*+json',
            ],
            EventController::class    => [
                'application/json',
                'application/*+json',
            ],
            LeadController::class     => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            ContactController::class  => [
                'application/json',
            ],
            DescribeController::class => [
                'application/json',
            ],
            EmailController::class    => [
                'application/json',
            ],
            EoiController::class      => [
                'application/json',
            ],
            EventController::class    => [
                'application/json',
            ],
            LeadController::class     => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        ContactController::class => [
            'POST' => ContactController::class,
        ],
        EmailController::class   => [
            'POST' => EmailController::class,
        ],
        EoiController::class     => [
            'POST' => EoiController::class,
        ],
        EventController::class   => [
            'POST' => EventController::class,
        ],
        LeadController::class    => [
            'POST' => LeadController::class,
        ],
    ],
    'input_filter_specs'     => [
        ContactController::class => [
            [
                'required'   => false,
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
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company_name',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'company_number',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'website',
            ],
            [
                'required'   => false,
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
                'required'   => false,
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
        ],
        EmailController::class   => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'template',
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
                'name'       => 'url',
            ],
        ],
        EoiController::class     => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'profile',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'account',
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
        ],
        EventController::class   => [
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'dietary',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'event',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'contact',
            ],
        ],
        LeadController::class    => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'email',
            ],
        ],
    ],
];
