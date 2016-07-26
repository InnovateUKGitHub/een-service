<?php

namespace ConsoleTest\Service;

use Console\Service\ConnectionService;
use Console\Service\HttpService;
use Zend\Http\Request;
use Zend\Http\Exception\InvalidArgumentException;

/**
 * @covers Console\Service\ConnectionService
 */
class ConnectionServiceTest extends \PHPUnit_Framework_TestCase
{
    const CONFIG = [
        'server' => 'test',
        'port' => '80',
        'accept' => 'application/json',
        'content-type' => 'application/json',
    ];

    /** @var \PHPUnit_Framework_MockObject_MockObject|HttpService $serviceMock */
    private $serviceMock;

    public function Setup()
    {
        $this->serviceMock = self::getMock(HttpService::class, [], [], '', false);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the server
     */
    public function testNoConfigThrowError()
    {
        new ConnectionService($this->serviceMock, []);
    }
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the port
     */
    public function testNoPortConfigThrowError()
    {
        new ConnectionService($this->serviceMock, ['server' => 'test']);
    }

    public function testExecute()
    {
        $service = new ConnectionService($this->serviceMock, self::CONFIG);

        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->willReturn([]);

        self::assertEquals([], $service->execute(Request::METHOD_GET, 'path'));
    }

    public function testExecuteWithBody()
    {
        $service = new ConnectionService($this->serviceMock, self::CONFIG);

        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->willReturn([]);

        self::assertEquals([], $service->execute(Request::METHOD_GET, 'path', ['body' => 'content']));
    }
}
