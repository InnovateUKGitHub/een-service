<?php

namespace SyncTest\Controller;

use Sync\Controller\SavedSearchesController;
use Sync\Service\SavedSearchesService;
use SyncTest\Bootstrap;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Request;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use Zend\Router\RouteStackInterface;

/**
 * @covers \Sync\Controller\SavedSearchesController
 */
class SavedSearchesControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|SavedSearchesService */
    private $service;

    public function Setup()
    {
        $this->service = $this->createMock(SavedSearchesService::class);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage This is a console tool only
     */
    public function testIndexActionNotConsole()
    {
        $controller = $this->buildController(['action' => 'index']);

        $controller->dispatch($controller->getRequest());
    }

    private function buildController($routMatch)
    {
        $controller = new SavedSearchesController($this->service);

        $serviceManager = Bootstrap::getServiceManager();
        /** @var RouteStackInterface $router */
        $router = $serviceManager->get('HttpRouter');
        $routeMatch = new RouteMatch($routMatch);

        $event = new MvcEvent();
        $event->setRouter($router);
        $event->setRouteMatch($routeMatch);

        $controller->setEvent($event);

        return $controller;
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Please specify the user to send the search results
     */
    public function testIndexActionInvalidMonth()
    {
        $controller = $this->buildController(['user' => '', 'action' => 'index']);

        $request = new Request();
        $controller->dispatch($request);
    }

    public function testIndexAction()
    {
        $controller = $this->buildController(['user' => 'userId', 'action' => 'index']);

        $request = new Request();
        self::assertEquals(
            "Saved Searches transferred to SalesForce.\n",
            $controller->dispatch($request)
        );
    }
}
