<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Validator\MerlinValidatorFactory;
use Console\Validator\MerlinValidator;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Validator\MerlinValidatorFactory
 */
class MerlinValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $config = [
            MerlinValidatorFactory::MERLIN_DATA_STRUCTURE => '',
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(MerlinValidatorFactory::CONFIG)
            ->willReturn($config);
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));

        self::assertInstanceOf(
            MerlinValidator::class,
            (new MerlinValidatorFactory())->__invoke($serviceManager)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the merlin data structure
     */
    public function testFactoryThrowException()
    {
        $config = [];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(MerlinValidatorFactory::CONFIG)
            ->willReturn($config);
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));

        self::assertInstanceOf(
            MerlinValidator::class,
            (new MerlinValidatorFactory())->__invoke($serviceManager)
        );
    }
}
