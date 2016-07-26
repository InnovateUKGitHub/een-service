<?php

namespace ConsoleTest;

use Console\Module;
use Zend\Console\Adapter\AdapterInterface;
use Console\Factory\Controller\GenerateControllerFactory;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Factory\Service\ConnectionServiceFactory;
use Console\Factory\Service\DeleteServiceFactory;
use Console\Factory\Service\GenerateServiceFactory;
use Console\Factory\Service\HttpServiceFactory;
use Console\Factory\Service\ImportServiceFactory;
use Console\Controller\GenerateController;
use Console\Controller\ImportController;
use Console\Service\ConnectionService;
use Console\Service\DeleteService;
use Console\Service\GenerateService;
use Console\Service\HttpService;
use Console\Service\ImportService;

/**
 * @covers Console\Module
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigIsCorrect()
    {
        $module = new Module();
        self::assertEquals([
            'controllers'     => [
                'factories' => [
                    GenerateController::class => GenerateControllerFactory::class,
                    ImportController::class   => ImportControllerFactory::class,
                ],
            ],
            'service_manager' => [
                'factories' => [
                    ConnectionService::class => ConnectionServiceFactory::class,
                    DeleteService::class     => DeleteServiceFactory::class,
                    GenerateService::class   => GenerateServiceFactory::class,
                    HttpService::class       => HttpServiceFactory::class,
                    ImportService::class     => ImportServiceFactory::class,
                ],
            ],
            'console'         => [
                'router' => [
                    'routes' => [
                        'import-data' => [
                            'options' => [
                                'route'       => 'import [--type=<type>]',
                                'constraints' => [
                                    'type' => '[bo|all]',
                                ],
                                'defaults'    => [
                                    'controller' => ImportController::class,
                                    'action'     => 'import',
                                ],
                            ],
                        ],
                        'generate-data' => [
                            'options' => [
                                'route'       => 'generate [--index=<index>] [--number=<number>]',
                                'constraints' => [
                                    'index' => '[opportunity|event|all]',
                                ],
                                'defaults'    => [
                                    'controller' => GenerateController::class,
                                    'action'     => 'generate',
                                ],
                            ],
                        ],
                        'delete-data'   => [
                            'options' => [
                                'route'       => 'delete [--index=<index>]',
                                'constraints' => [
                                    'index' => '[opportunity|event|all]',
                                ],
                                'defaults'    => [
                                    'controller' => GenerateController::class,
                                    'action'     => 'delete',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $module->getConfig());
    }

    public function testConsoleUsageIsCorrect()
    {
        $module = new Module();
        /** @var \PHPUnit_Framework_MockObject_MockObject|AdapterInterface $console */
        $console = self::getMock(AdapterInterface::class, [], [], '', false);
        self::assertEquals([
            'import [--type=<type>]' => 'import date from merlin into elasticSearch',
            ['--type', 'Type of data to be imported. [bo|all] (default: all)'],
            'generate [--index=<index>] [--number=<number>]' => 'Generate random data into elasticSearch for test purpose',
            ['--index', 'Index to generate. [opportunity|event|all] (default: all)'],
            ['--number', 'Number of documents to generate. (default: 10)'],
            'delete [--index=<index>]'                       => 'Delete elasticSearch index type',
            ['--index', 'Index to delete. [opportunity|event|all] (default: all)'],
        ], $module->getConsoleUsage($console));
    }
}
