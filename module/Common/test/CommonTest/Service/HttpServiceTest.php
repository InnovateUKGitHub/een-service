<?php

namespace ConsoleTest\Service;

use Common\Service\HttpService;
use Zend\Http\Client;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Http\Exception\RuntimeException;
use Zend\Http\Header\ContentType;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Json\Server\Exception\HttpException;

/**
 * @covers \Common\Service\HttpService
 */
class HttpServiceTest extends \PHPUnit_Framework_TestCase
{
    const HTTP_SCHEME = 'http';
    const SERVER = 'server';
    const PORT = 80;
    const PATH_TO_SERVICE = '/path-to-service';
    const REQUEST_BODY = 'request-body';
    const USER = 'user';
    const PASSWORD = 'password';
    const VERSION = 1;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Client */
    private $clientMock;

    public function Setup()
    {
        $this->clientMock = $this->createMock(Client::class);
    }

    public function testGetterSetter()
    {
        $service = new HttpService($this->clientMock);
        self::assertInstanceOf(HttpService::class, $service->setHttpMethod(Request::METHOD_GET));
        self::assertEquals(Request::METHOD_GET, $service->getHttpMethod());
        self::assertInstanceOf(HttpService::class, $service->setPathToService(self::PATH_TO_SERVICE));
        self::assertEquals(self::PATH_TO_SERVICE, $service->getPathToService());
        self::assertInstanceOf(HttpService::class, $service->setRequestBody(self::REQUEST_BODY));
        self::assertInstanceOf(HttpService::class, $service->setHeaders([]));
        self::assertInstanceOf(HttpService::class, $service->setQueryParams([]));
    }

    /**
     * @dataProvider testSetHttpMethodProvider
     *
     * @param      $method
     * @param bool $exception
     */
    public function testSetHttpMethod($method, $exception = false)
    {
        $service = new HttpService($this->clientMock);
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('Unsupported HTTP method ' . $method);
        }
        $service->setHttpMethod($method);
    }

    public function testSetHttpMethodProvider()
    {
        return [
            [Request::METHOD_GET],
            [Request::METHOD_PUT],
            [Request::METHOD_POST],
            [Request::METHOD_DELETE],
            [Request::METHOD_CONNECT, true],
            [Request::METHOD_HEAD, true],
            [Request::METHOD_OPTIONS, true],
            [Request::METHOD_PATCH, true],
            [Request::METHOD_PROPFIND, true],
            [Request::METHOD_TRACE, true],
        ];
    }

    public function testExecute()
    {
        $service = new HttpService($this->clientMock);
        $this->clientMock
            ->expects(self::once())
            ->method('setMethod')
            ->with(Request::METHOD_POST);
        $this->clientMock
            ->expects(self::once())
            ->method('setUri')
            ->with(self::HTTP_SCHEME . '://' . self::SERVER . self::PATH_TO_SERVICE);

        $responseMock = $this->createMock(Response::class);
        $headersMock = $this->createMock(Headers::class);
        $contentTypeMock = $this->createMock(ContentType::class);
        $responseMock->expects(self::once())
            ->method('getHeaders')
            ->willReturn($headersMock);
        $headersMock->expects(self::once())
            ->method('get')
            ->willReturn($contentTypeMock);
        $contentTypeMock->expects(self::once())
            ->method('getFieldValue')
            ->willReturn('text');

        $responseMock->expects(self::once())
            ->method('getContent')
            ->willReturn('{"success": 1}');

        $this->clientMock
            ->expects(self::once())
            ->method('send')
            ->willReturn($responseMock);

        $service->setRequestBody(self::REQUEST_BODY);
        $service->setServer(self::SERVER);

        self::assertEquals('{"success": 1}', $service->execute(
            Request::METHOD_POST,
            self::PATH_TO_SERVICE
        ));
    }

    public function testExecuteThrowExceptionAfterSendException()
    {
        $service = new HttpService($this->clientMock);

        $this->clientMock
            ->expects(self::once())
            ->method('send')
            ->willThrowException(new RuntimeException('Send Failed'));

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Send Failed');

        $service->execute(
            Request::METHOD_POST,
            self::PATH_TO_SERVICE
        );
    }
}
