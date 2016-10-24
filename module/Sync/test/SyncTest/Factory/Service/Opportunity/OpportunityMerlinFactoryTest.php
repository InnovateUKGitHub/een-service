<?php

namespace SyncTest\Factory\Service\Opportunity;

use Common\Constant\EEN;
use Sync\Factory\Service\Opportunity\OpportunityMerlinFactory;
use Sync\Service\Opportunity\OpportunityMerlin;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\Opportunity\OpportunityMerlinFactory
 */
class OpportunityMerlinFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $config = [
            EEN::MERLIN => [
                EEN::SERVER       => '',
                EEN::PATH_PROFILE => '',
                EEN::USERNAME     => '',
                EEN::PASSWORD     => '',
            ],
            EEN::CURL   => [
                EEN::MAX_CONNECTION   => '',
                EEN::FRESH_CONNECTION => '',
                EEN::TIMEOUT          => '',
            ],
        ];

        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));
        $serviceManager->expects(self::at(2))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::assertInstanceOf(
            OpportunityMerlin::class,
            (new OpportunityMerlinFactory())->__invoke($serviceManager)
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
        $config = array_merge(
            $config,
            [
                EEN::CURL => [
                    EEN::MAX_CONNECTION   => '',
                    EEN::FRESH_CONNECTION => '',
                    EEN::TIMEOUT          => '',
                ],
            ]
        );

        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);
        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        (new OpportunityMerlinFactory())->__invoke($serviceManager);
    }

    public function factoryException()
    {
        return [
            [
                'config'  => [],
                'message' => 'The config file is incorrect. Please specify the merlin information',
            ],
            [
                'config'  => [
                    EEN::MERLIN => [],
                ],
                'message' => 'The config file is incorrect. Please specify the server',
            ],
            [
                'config'  => [
                    EEN::MERLIN => [
                        EEN::SERVER => '',
                    ],
                ],
                'message' => 'The config file is incorrect. Please specify the path_get_profile',
            ],
            [
                'config'  => [
                    EEN::MERLIN => [
                        EEN::SERVER       => '',
                        EEN::PATH_PROFILE => '',
                    ],
                ],
                'message' => 'The config file is incorrect. Please specify the username',
            ],
            [
                'config'  => [
                    EEN::MERLIN => [
                        EEN::SERVER       => '',
                        EEN::PATH_PROFILE => '',
                        EEN::USERNAME     => '',
                    ],
                ],
                'message' => 'The config file is incorrect. Please specify the password',
            ],
        ];
    }
}
