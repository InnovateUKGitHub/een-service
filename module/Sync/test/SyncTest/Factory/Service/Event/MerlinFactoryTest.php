<?php

namespace SyncTest\Factory\Service\Event;

use Common\Constant\EEN;
use Sync\Factory\Service\Event\MerlinFactory;
use Sync\Service\Event\Merlin;
use Sync\Service\Event\MerlinConnection;
use Sync\Service\IndexService;
use Sync\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Sync\Factory\Service\Event\MerlinFactory
 */
class MerlinFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = $this->createMock(ServiceManager::class);

        $config = [
            EEN::MERLIN_EVENT_STRUCTURE => '',
        ];

        $serviceManager->expects(self::at(0))
            ->method('get')
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));
        $serviceManager->expects(self::at(1))
            ->method('get')
            ->with(MerlinConnection::class)
            ->willReturn($this->createMock(MerlinConnection::class));
        $serviceManager->expects(self::at(2))
            ->method('get')
            ->with(MerlinValidator::class)
            ->willReturn($this->createMock(MerlinValidator::class));
        $serviceManager->expects(self::at(3))
            ->method('get')
            ->with(EEN::CONFIG)
            ->willReturn($config);

        self::assertInstanceOf(
            Merlin::class,
            (new MerlinFactory())->__invoke($serviceManager)
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

        (new MerlinFactory())->__invoke($serviceManager);
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
