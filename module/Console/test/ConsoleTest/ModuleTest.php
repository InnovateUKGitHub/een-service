<?php
namespace ConsoleTest;

use Console\Controller\GenerateController;
use Console\Controller\ImportController;
use Console\Factory\Controller\GenerateControllerFactory;
use Console\Factory\Controller\ImportControllerFactory;
use Console\Factory\Service\GenerateServiceFactory;
use Console\Factory\Service\HttpServiceFactory;
use Console\Factory\Service\Import\DeleteServiceFactory;
use Console\Factory\Service\Import\Event\EventBriteFactory;
use Console\Factory\Service\Import\Event\MerlinIngestFactory;
use Console\Factory\Service\Import\EventServiceFactory;
use Console\Factory\Service\Import\ImportServiceFactory;
use Console\Factory\Service\Import\OpportunityServiceFactory;
use Console\Factory\Service\IndexServiceFactory;
use Console\Factory\Service\Merlin\EventMerlinFactory;
use Console\Factory\Service\Merlin\OpportunityMerlinFactory;
use Console\Factory\Service\PurgeServiceFactory;
use Console\Factory\Validator\MerlinValidatorFactory;
use Console\Module;
use Console\Service\GenerateService;
use Console\Service\HttpService;
use Console\Service\Import\DeleteService;
use Console\Service\Import\Event\EventBrite;
use Console\Service\Import\Event\MerlinIngest;
use Console\Service\Import\EventService;
use Console\Service\Import\ImportService;
use Console\Service\Import\OpportunityService;
use Console\Service\IndexService;
use Console\Service\Merlin\EventMerlin;
use Console\Service\Merlin\OpportunityMerlin;
use Console\Service\PurgeService;
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
                    GenerateService::class    => GenerateServiceFactory::class,
                    HttpService::class        => HttpServiceFactory::class,
                    IndexService::class       => IndexServiceFactory::class,
                    MerlinValidator::class    => MerlinValidatorFactory::class,
                    PurgeService::class       => PurgeServiceFactory::class,
                    ImportService::class      => ImportServiceFactory::class,
                    DeleteService::class      => DeleteServiceFactory::class,
                    OpportunityService::class => OpportunityServiceFactory::class,
                    EventService::class       => EventServiceFactory::class,
                    OpportunityMerlin::class  => OpportunityMerlinFactory::class,
                    EventMerlin::class        => EventMerlinFactory::class,
                    MerlinIngest::class       => MerlinIngestFactory::class,
                    EventBrite::class         => EventBriteFactory::class,
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
                                'route'       => 'import [--index=<index>] [--month=<month>] [--type=<type>]',
                                'constraints' => [
                                    'month' => '[1|2|3|4|5|6|7|8|9|10|11|12]',
                                    'type'  => '[s|u]',
                                    'index' => '[opportunity|event]',
                                ],
                                'defaults'    => [
                                    'controller' => ImportController::class,
                                    'action'     => 'import',
                                ],
                            ],
                        ],
                        'delete-data'   => [
                            'options' => [
                                'route'       => 'delete [--index=<index>] [--since=<since>]',
                                'constraints' => [
                                    'since' => '[\d]+',
                                    'index' => '[opportunity|event]',
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
            'import [--month=<month>] [--type=<type>]'       => 'import date from merlin into elasticSearch',
            ['--month', 'The number amount of month to go back. [1|2|3|4|5|6|7|8|9|10|11|12] (default: 1)'],
            ['--type', 'The type of data to import from. [s|u] (default: u)'],
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
