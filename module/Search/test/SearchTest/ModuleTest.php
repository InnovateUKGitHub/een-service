<?php

namespace SearchTest;

use Search\Controller\CountryController;
use Search\Controller\EventsController;
use Search\Controller\OpportunitiesController;
use Search\Factory\Controller\CountryControllerFactory;
use Search\Factory\Controller\EventsControllerFactory;
use Search\Factory\Controller\OpportunitiesControllerFactory;
use Search\Factory\Service\ElasticSearchServiceFactory;
use Search\Factory\Service\QueryServiceFactory;
use Search\Module;
use Search\Service\ElasticSearchService;
use Search\Service\QueryService;
use Zend\Router\Http\Literal;
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
                    QueryService::class         => QueryServiceFactory::class,
                    ElasticSearchService::class => ElasticSearchServiceFactory::class,
                ],
            ],
            $config['service_manager']
        );
        self::assertEquals(
            [
                'factories' => [
                    CountryController::class => CountryControllerFactory::class,
                    EventsController::class => EventsControllerFactory::class,
                    OpportunitiesController::class => OpportunitiesControllerFactory::class,
                ],
            ],
            $config['controllers']
        );
        self::assertEquals(
            [
                'routes' => [
                    'een.countries'     => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/countries',
                            'defaults' => [
                                'controller' => CountryController::class,
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
            $config['router']
        );
        self::assertEquals(
            [
                'controllers'            => [
                    CountryController::class => 'Json',
                    EventsController::class => 'Json',
                    OpportunitiesController::class => 'Json',
                ],
                'accept_whitelist'       => [
                    CountryController::class => [
                        'application/json',
                        'application/*+json',
                    ],
                    EventsController::class => [
                        'application/json',
                        'application/*+json',
                    ],
                    OpportunitiesController::class => [
                        'application/json',
                        'application/*+json',
                    ],
                ],
                'content_type_whitelist' => [
                    CountryController::class => [
                        'application/json',
                    ],
                    EventsController::class => [
                        'application/json',
                    ],
                    OpportunitiesController::class => [
                        'application/json',
                    ],
                ],
            ],
            $config['zf-content-negotiation']
        );
        self::assertEquals(
            [
                EventsController::class => [
                    'POST' => EventsController::class,
                ],
                OpportunitiesController::class => [
                    'POST' => OpportunitiesController::class,
                ],
            ],
            $config['zf-content-validation']
        );
        self::assertEquals(
            [
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
                        'name'       => 'country',
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
