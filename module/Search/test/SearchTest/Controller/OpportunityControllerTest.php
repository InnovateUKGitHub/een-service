<?php

namespace ConsoleTest\Controller;

use Search\Controller\OpportunitiesController;
use Search\Service\ElasticSearchService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers Search\Controller\OpportunitiesController
 */
class OpportunitiesControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ElasticSearchService $elasticSearchServiceMock */
        $elasticSearchServiceMock = $this->createMock(ElasticSearchService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $elasticSearchServiceMock->expects(self::once())
            ->method('searchOpportunities')
            ->with(['params' => 'myParams'])
            ->willReturn(['success' => true]);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['params' => 'myParams']);

        $controller = new OpportunitiesController($elasticSearchServiceMock);
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
        $id = 'myOpportunityId';

        /** @var \PHPUnit_Framework_MockObject_MockObject|ElasticSearchService $elasticSearchServiceMock */
        $elasticSearchServiceMock = $this->createMock(ElasticSearchService::class);

        $elasticSearchServiceMock->expects(self::once())
            ->method('searchOpportunity')
            ->with($id)
            ->willReturn(['found' => true]);

        $controller = new OpportunitiesController($elasticSearchServiceMock);
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
