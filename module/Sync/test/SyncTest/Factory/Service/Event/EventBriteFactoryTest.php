<?php

namespace SyncTest\Factory\Service\Event;

use Common\Constant\EEN;
use Sync\Factory\Service\Event\EventBriteFactory;
use Sync\Service\Event\EventBrite;
use Sync\Service\IndexService;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\Event\EventBriteFactory
 */
class EventBriteFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $config = [
            EEN::EVENT_BRITE => [
                EEN::SCHEME     => '',
                EEN::SERVER     => '',
                EEN::TOKEN      => '',
                EEN::PATH_EVENT => '',
            ],
            EEN::CURL        => [
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
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));
        $serviceManager->expects(self::at(3))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::assertInstanceOf(
            EventBrite::class,
            (new EventBriteFactory())->__invoke($serviceManager)
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
            ->expects(self::at(3))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        (new EventBriteFactory())->__invoke($serviceManager);
    }

    public function factoryException()
    {
        return [
            [
                'config'  => [],
                'message' => 'The config file is incorrect. Please specify the event-brite data',
            ],
            [
                'config'  => [
                    EEN::EVENT_BRITE => [],
                ],
                'message' => 'The config file is incorrect. Please specify the event-brite server',
            ],
            [
                'config'  => [
                    EEN::EVENT_BRITE => [
                        EEN::SERVER => '',
                    ],
                ],
                'message' => 'The config file is incorrect. Please specify the event-brite scheme',
            ],
            [
                'config'  => [
                    EEN::EVENT_BRITE => [
                        EEN::SERVER => '',
                        EEN::SCHEME => '',
                    ],
                ],
                'message' => 'The config file is incorrect. Please specify the event-brite token',
            ],
            [
                'config'  => [
                    EEN::EVENT_BRITE => [
                        EEN::SERVER => '',
                        EEN::SCHEME => '',
                        EEN::TOKEN  => '',
                    ],
                ],
                'message' => 'The config file is incorrect. Please specify the event-brite path',
            ],
        ];
    }
}
