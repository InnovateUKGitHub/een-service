<?php

namespace ConsoleTest\Controller;

use Console\Controller\ImportController;
use Console\Service\Import\DeleteService;
use Console\Service\Import\ImportService;
use ConsoleTest\Bootstrap;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use Zend\Router\RouteStackInterface;

/**
 * @covers Console\Controller\ImportController
 */
class ImportControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|ImportService */
    private $importService;
    /** @var \PHPUnit_Framework_MockObject_MockObject|DeleteService */
    private $deleteService;

    public function Setup()
    {
        $this->importService = $this->createMock(ImportService::class);
        $this->deleteService = $this->createMock(DeleteService::class);
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

    private function buildController($routMatch)
    {
        $controller = new ImportController($this->importService, $this->deleteService);

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
