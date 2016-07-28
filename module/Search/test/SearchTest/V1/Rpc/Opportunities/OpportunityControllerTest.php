<?php

namespace ConsoleTest\Controller;

use Search\V1\ElasticSearch\Service\ElasticSearchService;
use Search\V1\Merlin\Service\MerlinService;
use Search\V1\Rpc\Opportunities\OpportunitiesController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use ZF\ContentNegotiation\ViewModel;

/**
 * @covers Search\V1\Rpc\Opportunities\OpportunitiesController
 */
class OpportunitiesControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOpportunitiesAction()
    {
        $elasticSearchServiceMock = self::getMock(ElasticSearchService::class, [], [], '', false);
        $merlinServiceMock = self::getMock(MerlinService::class, [], [], '', false);
        $inputFilterMock = self::getMock('ZF\ContentValidation\InputFilter', ['getValues'], [], '', false);

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
        $event->setParam('ZF\ContentValidation\InputFilter', $inputFilterMock);

        $controller->setEvent($event);
        self::assertInstanceOf(ViewModel::class, $controller->dispatch($controller->getRequest()));
    }

}
