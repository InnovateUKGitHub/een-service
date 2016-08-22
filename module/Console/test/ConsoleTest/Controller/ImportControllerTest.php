<?php

namespace ConsoleTest\Controller;

use Console\Controller\ImportController;
use Console\Service\ImportService;
use ConsoleTest\Bootstrap;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;

/**
 * @covers Console\Controller\ImportController
 */
class ImportControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|ImportService */
    private $importService;

    public function Setup()
    {
        $this->importService = $this->createMock(ImportService::class);
    }

    private function buildController($routMatch)
    {
        $controller = new ImportController($this->importService);

        $serviceManager = Bootstrap::getServiceManager();
        /** @var RouteStackInterface $router */
        $router = $serviceManager->get('HttpRouter');
        $routeMatch = new RouteMatch($routMatch);

        $event = new MvcEvent();
        $event->setRouter($router);
        $event->setRouteMatch($routeMatch);

        $pluginManager = new PluginManager();
        $controller->setEvent($event);
        $controller->setPluginManager($pluginManager);
        $controller->setServiceLocator($serviceManager);

        return $controller;
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage This is a console tool only
     */
    public function testGenerateActionNotConsole()
    {
        $controller = $this->buildController(['action' => 'import']);

        $controller->dispatch($controller->getRequest());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The month enter is not valid
     */
    public function testImportActionInvalidMonth()
    {
        $controller = $this->buildController(['month' => 'invalidMonth', 'action' => 'import']);

        $request = new Request();
        $controller->dispatch($request);
    }

    public function testImportAction()
    {
        $controller = $this->buildController(['action' => 'import']);

        $request = new Request();
        self::assertEquals(['success' => true], $controller->dispatch($request));
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage This is a console tool only
     */
    public function testDeleteActionNotConsole()
    {
        $controller = $this->buildController(['action' => 'delete']);

        $controller->dispatch($controller->getRequest());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The month enter is not valid
     */
    public function testDeleteActionInvalidMonth()
    {
        $controller = $this->buildController(['since' => 'invalidSince', 'action' => 'delete']);

        $request = new Request();
        $controller->dispatch($request);
    }

    public function testDeleteAction()
    {
        $controller = $this->buildController(['action' => 'delete']);

        $request = new Request();
        self::assertEquals(['success' => true], $controller->dispatch($request));
    }
}
