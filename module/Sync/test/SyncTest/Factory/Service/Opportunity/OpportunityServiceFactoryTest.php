<?php

namespace SyncTest\Factory\Service\Opportunity;

use Common\Constant\EEN;
use Sync\Factory\Service\Opportunity\OpportunityServiceFactory;
use Sync\Service\IndexService;
use Sync\Service\Opportunity\OpportunityMerlin;
use Sync\Service\Opportunity\OpportunityService;
use Sync\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\Opportunity\OpportunityServiceFactory
 */
class OpportunityServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $config = [
            EEN::MERLIN_PROFILE_STRUCTURE => [],
        ];

        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(OpportunityMerlin::class)
            ->willReturn($this->createMock(OpportunityMerlin::class));
        $serviceManager->expects(self::at(2))
            ->method('get')
            ->with(MerlinValidator::class)
            ->willReturn($this->createMock(MerlinValidator::class));
        $serviceManager->expects(self::at(3))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::assertInstanceOf(
            OpportunityService::class,
            (new OpportunityServiceFactory())->__invoke($serviceManager)
        );
    }

    /**
     * @param array  $config
     * @param string $message
     *
     * @dataProvider factoryException
     */
    public function testFactoryThrowException($config, $message)
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(3))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        (new OpportunityServiceFactory())->__invoke($serviceManager);
    }

    public function factoryException()
    {
        return [
            [
                'config'  => [],
                'message' => 'The config file is incorrect. Please specify the merlin data structure',
            ],
        ];
    }
}
