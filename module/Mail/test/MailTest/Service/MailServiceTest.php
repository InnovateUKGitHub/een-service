<?php

namespace MailTest\Service;

use Common\Service\HttpService;
use Mail\Service\MailService;
use Zend\Http\Request;

/**
 * @covers \Mail\Service\MailService
 */
class MailServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var HttpService|\PHPUnit_Framework_MockObject_MockObject */
    private $serviceMock;
    /** @var MailService */
    private $service;

    public function testCreate()
    {
        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_POST, '/messages/email', [], [])
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $this->service->send([])
        );
    }

    public function testGet()
    {
        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, '/messages/email/1')
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $this->service->get(1)
        );
    }

    protected function Setup()
    {
        $this->serviceMock = $this->createMock(HttpService::class);
        $this->service = new MailService($this->serviceMock);
    }
}
