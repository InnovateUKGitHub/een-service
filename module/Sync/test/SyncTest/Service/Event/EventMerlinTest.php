<?php

namespace SyncTest\Service\Import\Event;

use Common\Service\HttpService;
use Sync\Service\Event\EventMerlin;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;
use Zend\Log\Logger;

/**
 * @covers \Sync\Service\Event\EventMerlin
 */
class EventMerlinTest extends \PHPUnit_Framework_TestCase
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PATH = '/path';

    /** @var \PHPUnit_Framework_MockObject_MockObject|HttpService $clientMock */
    private $clientMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Logger $loggerMock */
    private $loggerMock;
    /** @var EventMerlin $service */
    private $service;

    public function testGetList()
    {
        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willReturn('<profiles></profiles>');
        $this->service->getList();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the retrieve of the merlin events
     */
    public function testGetListThrowHttpException()
    {
        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willThrowException(new HttpException('HttpService Error'));
        $this->loggerMock->expects(self::at(0))
            ->method('debug')
            ->with('An error occurred during the retrieve of the merlin events');
        $this->loggerMock->expects(self::at(1))
            ->method('debug')
            ->with('HttpService Error');
        $this->service->getList();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the retrieve of the merlin events
     */
    public function testGetListThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willThrowException(new \Exception('HttpService Error'));
        $this->loggerMock->expects(self::at(0))
            ->method('debug')
            ->with('An error occurred during the retrieve of the merlin events');
        $this->loggerMock->expects(self::at(1))
            ->method('debug')
            ->with('HttpService Error');
        $this->service->getList();
    }

    protected function Setup()
    {
        $this->clientMock = $this->createMock(HttpService::class);
        $this->loggerMock = $this->createMock(Logger::class);

        $this->service = new EventMerlin(
            $this->clientMock,
            $this->loggerMock,
            self::USERNAME,
            self::PASSWORD,
            self::PATH
        );
    }
}
