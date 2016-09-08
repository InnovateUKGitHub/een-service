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
        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::once())
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));

        self::assertInstanceOf(
            MerlinValidator::class,
            (new MerlinValidatorFactory())->__invoke($serviceManager)
        );
    }
}
