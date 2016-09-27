<?php

namespace Mail;

use Mail\Controller\MailController;
use Mail\Factory\Controller\MailControllerFactory;
use Mail\Factory\Service\MailServiceFactory;
use Mail\Service\MailService;
use Zend\Router\Http\Segment;

return [
    'service_manager'        => [
        'factories' => [
            MailService::class => MailServiceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            MailController::class => MailControllerFactory::class,
        ],
    ],
    'router'                 => [
        'routes' => [
            'een.mail' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/mail[/:id]',
                    'constraints' => [
                        'id' => '[\d]+',
                    ],
                    'defaults'    => [
                        'controller' => MailController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            MailController::class => 'Json',
        ],
        'accept_whitelist'       => [
            MailController::class => [
                'application/json',
                'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            MailController::class => [
                'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        MailController::class => [
            'POST' => MailController::class,
        ],
    ],
    'input_filter_specs'     => [
        MailController::class => [
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
    ],
];
