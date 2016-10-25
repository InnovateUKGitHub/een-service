<?php

namespace Mail;

use Mail\Controller\MailController;
use Mail\Controller\TemplateController;
use Mail\Factory\Controller\MailControllerFactory;
use Mail\Factory\Controller\TemplateControllerFactory;
use Mail\Factory\Service\MailServiceFactory;
use Mail\Factory\Service\TemplateServiceFactory;
use Mail\Service\MailService;
use Mail\Service\TemplateService;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            MailService::class     => MailServiceFactory::class,
            TemplateService::class => TemplateServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            MailController::class     => MailControllerFactory::class,
            TemplateController::class => TemplateControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
            'een.email'          => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/email[/:id]',
                    'constraints' => [
                        'id' => '[\d]+',
                    ],
                    'defaults'    => [
                        'controller' => MailController::class,
                    ],
                ],
            ],
            'een.email.template' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/templates/email[/:id]',
                    'constraints' => [
                        'id' => '[\d\w\-]+',
                    ],
                    'defaults'    => [
                        'controller' => TemplateController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            MailController::class     => 'Json',
            TemplateController::class => 'Json',
        ],
        'accept_whitelist'       => [
            MailController::class     => [
                'application/json',
                'application/*+json',
            ],
            TemplateController::class => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            MailController::class     => [
                'application/json',
            ],
            TemplateController::class => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        MailController::class     => [
            'POST' => MailController::class,
        ],
        TemplateController::class => [
            'POST' => TemplateController::class,
            'PUT'  => TemplateController::class,
        ],
    ],
    'input_filter_specs'     => [
        MailController::class     => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'recipients',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'subject',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'body',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'macros',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => '_links',
            ],
        ],
        TemplateController::class => [
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'id',
            ],
            [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'subject',
            ],
            [
                'required'   => false,
                'validators' => [],
                'filters'    => [],
                'name'       => 'macros',
            ],
        ],
    ],
];
