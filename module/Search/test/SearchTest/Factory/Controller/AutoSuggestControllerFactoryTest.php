<?php

namespace SearchTest\Factory\Controller;

use Search\Controller\AutoSuggestController;
use Search\Factory\Controller\AutoSuggestControllerFactory;
use Search\Service\QueryService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Search\Factory\Controller\AutoSuggestControllerFactory
 */
class AutoSuggestControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(QueryService::class)
            ->willReturn($this->createMock(QueryService::class));

        self::assertInstanceOf(
            AutoSuggestController::class,
            (new AutoSuggestControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}