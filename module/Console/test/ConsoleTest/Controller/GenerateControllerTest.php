<?php

namespace ConsoleTest\Controller;

use Console\Controller\GenerateController;
use Console\Service\GenerateService;
use Console\Service\PurgeService;
use ConsoleTest\Bootstrap;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\Console\Router\RouteMatch;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteStackInterface;

/**
 * @covers \Console\Controller\GenerateController
 */
class GenerateControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|GenerateService */
    private $generateServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|PurgeService */
    private $purgeServiceMock;

    public function Setup()
    {
        $this->generateServiceMock = $this->createMock(GenerateService::class);
        $this->purgeServiceMock = $this->createMock(PurgeService::class);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage This is a console tool only
     */
    public function testGenerateActionNotConsole()
    {
        $controller = $this->buildController(['action' => 'generate']);

        $controller->dispatch($controller->getRequest());
    }

    private function buildController($routMatch)
    {
        $controller = new GenerateController($this->generateServiceMock, $this->purgeServiceMock);

        $serviceManager = Bootstrap::getServiceManager();
        /** @var RouteStackInterface $router */
        $router = $serviceManager->get('HttpRouter');
        $routeMatch = new RouteMatch($routMatch, count($routMatch));

        $event = new MvcEvent();
        $event->setRouter($router);
        $event->setRouteMatch($routeMatch);

        $controller->setEvent($event);

        return $controller;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The index enter is not valid
     */
    public function testGenerateActionInvalidIndex()
    {
        $controller = $this->buildController(['index' => 'invalidIndex', 'action' => 'generate']);

        $request = new Request();
        $controller->dispatch($request);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The number enter is not valid
     */
    public function testGenerateActionInvalidNumber()
    {
        $controller = $this->buildController(['number' => 'invalidNumber', 'action' => 'generate']);

        $request = new Request();
        $controller->dispatch($request);
    }

    public function testGenerateAction()
    {
        $controller = $this->buildController(['action' => 'generate']);

        $request = new Request();
        self::assertEquals("100 documents generated on all.\n", $controller->dispatch($request));
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage This is a console tool only
     */
    public function testDeleteActionNotConsole()
    {
        $controller = $this->buildController(['action' => 'purge']);

        $controller->dispatch($controller->getRequest());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The index enter is not valid
     */
    public function testDeleteActionInvalidIndex()
    {
        $controller = $this->buildController(['index' => 'invalidIndex', 'action' => 'purge']);

        $request = new Request();
        $controller->dispatch($request);
    }

    public function testDeleteAction()
    {
        $controller = $this->buildController(['action' => 'purge']);

        $request = new Request();
        self::assertEquals("Purge done on all.\n", $controller->dispatch($request));
    }
}
