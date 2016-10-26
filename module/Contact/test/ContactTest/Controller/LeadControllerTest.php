<?php

namespace ContactTest\Controller;

use Contact\Controller\LeadController;
use Contact\Service\LeadService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers \Contact\Controller\LeadController
 */
class LeadControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $service = $this->createMock(LeadService::class);

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

        $controller = new LeadController($service);
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
}
