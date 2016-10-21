<?php

namespace ContactTest\Controller;

use Contact\Controller\DescribeController;
use Contact\Service\ContactService;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;

/**
 * @covers \Contact\Controller\DescribeController
 */
class DescribeControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $id = 'accountId';

        $service = $this->createMock(ContactService::class);

        $service->expects(self::once())
            ->method('describe')
            ->with($id)
            ->willReturn(['found' => true]);

        $controller = new DescribeController($service);
        $routeMatch = new RouteMatch(['id' => $id]);

        $event = new MvcEvent();
        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $request = new Request();
        $request->setMethod(Request::METHOD_GET);

        self::assertEquals(
            ['found' => true],
            $controller->dispatch($request)
        );
    }
}
