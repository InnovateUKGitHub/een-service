<?php

namespace ContactTest\Controller;

use Contact\Controller\ContactController;
use Contact\Service\ContactService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers \Contact\Controller\ContactController
 */
class ContactControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $service = $this->createMock(ContactService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $service->expects(self::once())
            ->method('create')
            ->with(['params' => 'myParams'])
            ->willReturn(['records' => true]);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['params' => 'myParams']);

        $controller = new ContactController($service);
        $routeMatch = new RouteMatch([]);

        $event = new MvcEvent();
        $event->setParam(InputFilter::class, $inputFilterMock);
        $event->setRouteMatch($routeMatch);

        $controller->setEvent($event);
        $controller->getPluginManager()->setService('getInputFilter', $inputFilterPluginMock);

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);

        self::assertTrue($controller->dispatch($request));
    }

    public function testGet()
    {
        $id = 'accountId';

        $service = $this->createMock(ContactService::class);

        $service->expects(self::once())
            ->method('getContact')
            ->with($id)
            ->willReturn(['records' => true, 'size' => 1]);

        $controller = new ContactController($service);
        $routeMatch = new RouteMatch(['id' => $id]);

        $event = new MvcEvent();
        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $request = new Request();
        $request->setMethod(Request::METHOD_GET);

        self::assertTrue($controller->dispatch($request));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User not found
     * @expectedExceptionCode    404
     */
    public function testGetNoResult()
    {
        $id = 'accountId';

        $service = $this->createMock(ContactService::class);

        $service->expects(self::once())
            ->method('getContact')
            ->with($id)
            ->willReturn(['size' => 0]);

        $controller = new ContactController($service);
        $routeMatch = new RouteMatch(['id' => $id]);

        $event = new MvcEvent();
        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $request = new Request();
        $request->setMethod(Request::METHOD_GET);

        self::assertTrue($controller->dispatch($request));
    }
}
