<?php

namespace SearchTest;

use Search\Controller\EventsController;
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\EventsControllerFactory;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Factory\Service\QueryServiceFactory;
use Search\Module;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\Router\Http\Segment;

/**
 * @covers Search\Module
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
                    ElasticSearchService::class => ElasticSearchServiceFactory::class,
                    QueryService::class         => QueryServiceFactory::class,
                ],
            ],
            $config['service_manager']
        );
        self::assertEquals(
            [
                'factories' => [
                    OpportunitiesController::class => OpportunitiesControllerFactory::class,
                    EventsController::class => EventsControllerFactory::class,
                ],
            ],
            $config['controllers']
        );
        self::assertEquals(
            [
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
                    'een.events' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/events[/:id]',
                            'constraints' => [
                                'id' => '[\d]+\.[\d]+',
                            ],
                            'defaults'    => [
                                'controller' => EventsController::class,
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
                    OpportunitiesController::class => 'Json',
                    EventsController::class => 'Json',
                ],
                'accept_whitelist'       => [
                    OpportunitiesController::class => [
                        'application/json',
                        'application/*+json',
                    ],
                    EventsController::class => [
                        'application/json',
                        'application/*+json',
                    ],
                ],
                'content_type_whitelist' => [
                    OpportunitiesController::class => [
                        'application/json',
                    ],
                    EventsController::class => [
                        'application/json',
                    ],
                ],
            ],
            $config['zf-content-negotiation']
        );
        self::assertEquals(
            [
                OpportunitiesController::class => [
                    'POST' => OpportunitiesController::class,
                ],
                EventsController::class => [
                    'POST' => EventsController::class,
                ],
            ],
            $config['zf-content-validation']
        );
        self::assertEquals(
            [
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
                EventsController::class => [
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
                        'name'       => 'search',
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
        self::assertArrayHasKey('Search', $result['Zend\Loader\StandardAutoloader']['namespaces']);
    }
}
