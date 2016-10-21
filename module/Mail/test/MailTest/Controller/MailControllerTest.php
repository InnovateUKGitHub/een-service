<?php

namespace MailTest\Controller;

use Mail\Controller\MailController;
use Mail\Service\MailService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers \Mail\Controller\MailController
 */
class MailControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $service = $this->createMock(MailService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $service->expects(self::once())
            ->method('send')
            ->with(['params' => 'myParams'])
            ->willReturn(['success' => true]);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['params' => 'myParams']);

        $controller = new MailController($service);
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

    public function testGet()
    {
        $id = 'accountId';

        $service = $this->createMock(MailService::class);

        $service->expects(self::once())
            ->method('get')
            ->with($id)
            ->willReturn(['found' => true]);

        $controller = new MailController($service);
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
