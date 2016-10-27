<?php

namespace SearchTest\Controller;

use Search\Controller\AutoSuggestController;
use Search\Service\QueryService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers \Search\Controller\AutoSuggestController
 */
class AutoSuggestControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $service = $this->createMock(QueryService::class);
        $service->expects(self::once())
            ->method('findTerm')
            ->with('A', 10)
            ->willReturn([]);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['search' => 'A', 'size' => 10]);

        $controller = new AutoSuggestController($service);
        $routeMatch = new RouteMatch([]);

        $event = new MvcEvent();
        $event->setParam(InputFilter::class, $inputFilterMock);
        $event->setRouteMatch($routeMatch);

        $controller->setEvent($event);
        $controller->getPluginManager()->setService('getInputFilter', $inputFilterPluginMock);

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);

        self::assertEquals(
            [],
            $controller->dispatch($request)
        );
    }
}
