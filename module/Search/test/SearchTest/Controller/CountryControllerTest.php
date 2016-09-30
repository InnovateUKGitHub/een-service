<?php

namespace SearchTest\Controller;

use Search\Controller\CountryController;
use Search\Service\QueryService;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;

/**
 * @covers \Search\Controller\CountryController
 */
class CountryControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetList()
    {
        $service = $this->createMock(QueryService::class);

        $service->expects(self::once())
            ->method('getCountryList')
            ->willReturn(['found' => true]);

        $controller = new CountryController($service);
        $routeMatch = new RouteMatch([]);
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
