<?php

namespace ConsoleTest\Controller;

use Search\Controller\OpportunitiesController;
use Search\Service\ElasticSearchService;
use Search\Service\MerlinService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use ZF\ContentNegotiation\ViewModel;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers Search\Controller\OpportunitiesController
 */
class OpportunitiesControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOpportunitiesAction()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ElasticSearchService $elasticSearchServiceMock */
        $elasticSearchServiceMock = $this->createMock(ElasticSearchService::class);
        /** @var \PHPUnit_Framework_MockObject_MockObject|MerlinService $merlinServiceMock */
        $merlinServiceMock = $this->createMock(MerlinService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $elasticSearchServiceMock->expects(self::once())
            ->method('searchOpportunities')
            ->with(['params' => 'myParams']);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn(['params' => 'myParams']);

        $controller = new OpportunitiesController($elasticSearchServiceMock, $merlinServiceMock);
        $routeMatch = new RouteMatch(['action' => 'opportunities']);

        $event = new MvcEvent();
        $event->setRouteMatch($routeMatch);
        $event->setParam(InputFilter::class, $inputFilterMock);

        $controller->setEvent($event);
        $controller->getPluginManager()->setService('getInputFilter', $inputFilterPluginMock);

        self::assertInstanceOf(ViewModel::class, $controller->dispatch($controller->getRequest()));
    }

}
