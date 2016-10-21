<?php

namespace ContactTest\Controller;

use Contact\Controller\EmailController;
use Mail\Service\MailService;
use Zend\Http\Request;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

/**
 * @covers \Contact\Controller\EmailController
 */
class EmailControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $data = [
            'email'    => 'email@email.com',
            'url'      => 'http://google.com',
            'template' => 'template',
        ];
        $params = [
            'recipients' => [
                [
                    'email'  => $data['email'],
                    'macros' => [
                        'email' => $data['email'],
                        'url'   => $data['url'],
                    ],
                ],
            ],
            '_links'     => [
                'email_template' => $data['template'],
            ],
        ];

        $service = $this->createMock(MailService::class);

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterPluginMock = $this->createMock(InputFilterPlugin::class);
        $inputFilterPluginMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($inputFilterMock);

        $inputFilterMock->expects(self::once())
            ->method('getValues')
            ->willReturn($data);

        $service->expects(self::once())
            ->method('send')
            ->with($params)
            ->willReturn(['success' => true]);

        $controller = new EmailController($service);
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
}
