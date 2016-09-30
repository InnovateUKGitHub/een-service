<?php

namespace SearchTest\Factory\Controller;

use Search\Controller\CountryController;
use Search\Factory\Controller\CountryControllerFactory;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Controller\CountryControllerFactory
 */
class CountryControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($this->createMock(QueryService::class));

        self::assertInstanceOf(
            CountryController::class,
            (new CountryControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}