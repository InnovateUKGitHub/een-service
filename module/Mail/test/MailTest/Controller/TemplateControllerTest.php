<?php

namespace MailTest\Controller;

use Mail\Controller\TemplateController;
use Mail\Service\TemplateService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers \Mail\Controller\TemplateController
 */
class TemplateControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $service = $this->createMock(TemplateService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $service->expects(self::once())
            ->method('create')
            ->with(['params' => 'myParams'])
            ->willReturn(['success' => true]);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['params' => 'myParams']);

        $controller = new TemplateController($service);
        $routeMatch = new RouteMatch([]);

        $event = new MvcEvent();
        $event->setParam(InputFilter::class, $inputFilterMock);
        $event->setRouteMatch($routeMatch);

        $controller->setEvent($event);
        $controller->getPluginManager()->setService('getInputFilter', $inputFilterPluginMock);

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);

        self::assertEquals(
            ['success' => true],
            $controller->dispatch($request)
        );
    }

    public function testUpdate()
    {
        $service = $this->createMock(TemplateService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $service->expects(self::once())
            ->method('update')
            ->with(1, ['params' => 'myParams'])
            ->willReturn(['success' => true]);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['params' => 'myParams']);

        $controller = new TemplateController($service);
        $routeMatch = new RouteMatch(['id' => 1]);

        $event = new MvcEvent();
        $event->setParam(InputFilter::class, $inputFilterMock);
        $event->setRouteMatch($routeMatch);

        $controller->setEvent($event);
        $controller->getPluginManager()->setService('getInputFilter', $inputFilterPluginMock);

        $request = new Request();
        $request->setMethod(Request::METHOD_PUT);

        self::assertEquals(
            ['success' => true],
            $controller->dispatch($request)
        );
    }

    public function testDelete()
    {
        $service = $this->createMock(TemplateService::class);

        $service->expects(self::once())
            ->method('delete')
            ->with(1)
            ->willReturn(['success' => true]);

        $controller = new TemplateController($service);
        $routeMatch = new RouteMatch(['id' => 1]);

        $event = new MvcEvent();
        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $request = new Request();
        $request->setMethod(Request::METHOD_DELETE);

        self::assertEquals(
            ['success' => true],
            $controller->dispatch($request)
        );
    }

    public function testGetList()
    {
        $service = $this->createMock(TemplateService::class);

        $service->expects(self::once())
            ->method('getList')
            ->willReturn(['found' => true]);

        $controller = new TemplateController($service);
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
