<?php

namespace SyncTest\Controller;

use Common\Constant\EEN;
use Sync\Controller\IndexController;
use Sync\Service\IndexService;
use SyncTest\Bootstrap;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Request;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use Zend\Router\RouteStackInterface;

/**
 * @covers \Sync\Controller\IndexController
 */
class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService */
    private $indexService;

    public function Setup()
    {
        $this->indexService = $this->createMock(IndexService::class);
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
        $controller = new IndexController($this->indexService);

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

    public function testIndexAction()
    {
        $controller = $this->buildController(['action' => 'index']);

        $this->indexService->expects(self::at(0))
            ->method('createIndex')
            ->with(EEN::ES_INDEX_EVENT);
        $this->indexService->expects(self::at(1))
            ->method('createIndex')
            ->with(EEN::ES_INDEX_OPPORTUNITY);
        $this->indexService->expects(self::at(2))
            ->method('createSettings')
            ->with(EEN::ES_INDEX_EVENT);
        $this->indexService->expects(self::at(3))
            ->method('createSettings')
            ->with(EEN::ES_INDEX_OPPORTUNITY);

        $request = new Request();
        self::assertEquals(
            "Index creation done.\n",
            $controller->dispatch($request)
        );
    }
}
