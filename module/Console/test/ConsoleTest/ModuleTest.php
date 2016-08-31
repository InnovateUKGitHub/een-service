<?php
namespace ConsoleTest;

use Console\Controller\GenerateController;
use Console\Controller\ImportController;
use Console\Factory\Controller\GenerateControllerFactory;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Factory\Service\DeleteServiceFactory;
use Console\Factory\Service\GenerateServiceFactory;
use Console\Factory\Service\HttpServiceFactory;
use Console\Factory\Service\ImportServiceFactory;
use Console\Factory\Service\IndexServiceFactory;
use Console\Factory\Validator\MerlinValidatorFactory;
use Console\Module;
use Console\Service\DeleteService;
use Console\Service\GenerateService;
use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;
use Zend\Console\Adapter\AdapterInterface;

/**
 * @covers Console\Module
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigIsCorrect()
    {
        $module = new Module();

        $config = $module->getConfig();

        self::assertArrayHasKey('controllers', $config);
        self::assertArrayHasKey('service_manager', $config);
        self::assertArrayHasKey('console', $config);
        self::assertArrayHasKey('view_manager', $config);

        self::assertEquals(
            [
                'factories' => [
                    GenerateController::class => GenerateControllerFactory::class,
                    ImportController::class   => ImportControllerFactory::class,
                ],
            ],
            $config['controllers']
        );
        self::assertEquals(
            [
                'factories' => [
                    DeleteService::class   => DeleteServiceFactory::class,
                    GenerateService::class => GenerateServiceFactory::class,
                    HttpService::class     => HttpServiceFactory::class,
                    ImportService::class   => ImportServiceFactory::class,
                    IndexService::class    => IndexServiceFactory::class,
                    MerlinValidator::class => MerlinValidatorFactory::class,
                ],
            ],
            $config['service_manager']
        );
        self::assertEquals(
            [
                'router' => [
                    'routes' => [
                        'import-data'   => [
                            'options' => [
                                'route'       => 'import [--month=<month>]',
                                'constraints' => [
                                    'month' => '[1|2|3|4|5|6|7|8|9|10|11|12]',
                                ],
                                'defaults'    => [
                                    'controller' => ImportController::class,
                                    'action'     => 'import',
                                ],
                            ],
                        ],
                        'delete-data'   => [
                            'options' => [
                                'route'       => 'delete [--since=<since>]',
                                'constraints' => [
                                    'since' => '[\d]+',
                                ],
                                'defaults'    => [
                                    'controller' => ImportController::class,
                                    'action'     => 'delete',
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
                        'delete-all'    => [
                            'options' => [
                                'route'       => 'delete-all [--index=<index>]',
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
            $config['console']
        );
    }

    public function testConsoleUsageIsCorrect()
    {
        $module = new Module();
        /** @var \PHPUnit_Framework_MockObject_MockObject|AdapterInterface $console */
        $console = $this->createMock(AdapterInterface::class);
        self::assertEquals([
            'import [--month=<month>]'                       => 'import date from merlin into elasticSearch',
            ['--month', 'The number amount of month to go back. [1|2|3|4|5|6|7|8|9|10|11|12] (default: 1)'],
            'delete [--since=<since>]'                       => 'import date from merlin into elasticSearch',
            ['--since', 'The number amount of month out of date (default: 12)'],
            'generate [--index=<index>] [--number=<number>]' => 'Generate random data into elasticSearch for test purpose',
            ['--index', 'Index to generate. [opportunity|event|all] (default: all)'],
            ['--number', 'Number of documents to generate. (default: 10)'],
            'delete-all [--index=<index>]'                   => 'Delete elasticSearch index type',
            ['--index', 'Index to delete. [opportunity|event|all] (default: all)'],
        ], $module->getConsoleUsage($console));
    }
}
