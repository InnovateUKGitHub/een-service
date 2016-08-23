<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Validator\MerlinValidatorFactory;
use Console\Validator\MerlinValidator;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceLocatorInterface;

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

        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        $serviceLocator
            ->expects(self::at(0))
            ->method('get')
            ->with(MerlinValidatorFactory::CONFIG)
            ->willReturn($config);
        $serviceLocator
            ->expects(self::at(1))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));

        self::assertInstanceOf(
            MerlinValidator::class,
            (new MerlinValidatorFactory())->createService($serviceLocator)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the merlin data structure
     */
    public function testFactoryThrowException()
    {
        $config = [];

        /* @var $serviceLocator ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        $serviceLocator
            ->expects(self::at(0))
            ->method('get')
            ->with(MerlinValidatorFactory::CONFIG)
            ->willReturn($config);
        $serviceLocator
            ->expects(self::at(1))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));

        self::assertInstanceOf(
            MerlinValidator::class,
            (new MerlinValidatorFactory())->createService($serviceLocator)
        );
    }
}
