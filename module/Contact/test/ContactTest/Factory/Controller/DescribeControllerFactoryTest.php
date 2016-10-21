<?php

namespace ContactTest\Factory\Controller;

use Contact\Controller\DescribeController;
use Contact\Factory\Controller\DescribeControllerFactory;
use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Contact\Factory\Controller\DescribeControllerFactory
 */
class DescribeControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManagerMock = $this->createMock(ServiceManager::class);
        $serviceManagerMock->expects(self::once())
            ->method('get')
            ->with(ContactService::class)
            ->willReturn($this->createMock(ContactService::class));

        self::assertInstanceOf(
            DescribeController::class,
            (new DescribeControllerFactory())->__invoke($serviceManagerMock)
        );
    }
}