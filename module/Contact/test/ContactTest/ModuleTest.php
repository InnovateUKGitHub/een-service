<?php

namespace ContactTest;

use Contact\Controller\ContactController;
use Contact\Factory\Controller\ContactControllerFactory;
use Contact\Factory\Service\ContactServiceFactory;
use Contact\Factory\Service\SalesForceServiceFactory;
use Contact\Module;
use Contact\Service\ContactService;
use Contact\Service\SalesForceService;
use Zend\Router\Http\Segment;

/**
 * @covers \Search\Module
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigIsCorrect()
    {
        $module = new Module();

        $config = $module->getConfig();

        self::assertArrayHasKey('service_manager', $config);
        self::assertArrayHasKey('controllers', $config);
        self::assertArrayHasKey('router', $config);
        self::assertArrayHasKey('zf-content-negotiation', $config);
        self::assertArrayHasKey('zf-content-validation', $config);
        self::assertArrayHasKey('input_filter_specs', $config);
        self::assertArrayHasKey('view_manager', $config);

        self::assertEquals(
            [
                'factories' => [
                    ContactService::class    => ContactServiceFactory::class,
                    SalesForceService::class => SalesForceServiceFactory::class,
                ],
            ],
            $config['service_manager']
        );
        self::assertEquals(
            [
                'factories' => [
                    ContactController::class => ContactControllerFactory::class,
                ],
            ],
            $config['controllers']
        );
        self::assertEquals(
            [
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
            $config['router']
        );
        self::assertEquals(
            [
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
            $config['zf-content-negotiation']
        );
        self::assertEquals(
            [
                ContactController::class => [
                    'POST' => ContactController::class,
                ],
            ],
            $config['zf-content-validation']
        );
        self::assertEquals(
            [
                ContactController::class => [
                    [
                        'required'   => true,
                        'validators' => [],
                        'filters'    => [],
                        'name'       => 'name',
                    ],
                ],
            ],
            $config['input_filter_specs']
        );
        self::assertEquals(
            [
                'strategies' => [
                    'ViewJsonStrategy',
                ],
            ],
            $config['view_manager']
        );
    }

    public function testAutoloaderConfig()
    {
        $module = new Module();

        $result = $module->getAutoloaderConfig();

        self::assertArrayHasKey('Zend\Loader\StandardAutoloader', $result);
        self::assertArrayHasKey('namespaces', $result['Zend\Loader\StandardAutoloader']);
        self::assertArrayHasKey('Contact', $result['Zend\Loader\StandardAutoloader']['namespaces']);
    }
}
