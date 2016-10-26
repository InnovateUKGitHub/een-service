<?php

namespace SyncTest\Service\Import\Opportunity;

use Common\Service\HttpService;
use Sync\Service\Opportunity\OpportunityMerlin;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;
use Zend\Log\Logger;

/**
 * @covers \Sync\Service\Opportunity\OpportunityMerlin
 */
class OpportunityMerlinTest extends \PHPUnit_Framework_TestCase
{
    const MONTH = 1;
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PATH = '/path';

    /** @var \PHPUnit_Framework_MockObject_MockObject|HttpService $clientMock */
    private $clientMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Logger $loggerMock */
    private $loggerMock;
    /** @var OpportunityMerlin $service */
    private $service;

    public function testGetList()
    {
        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willReturn('<profiles></profiles>');
        $this->service->getList(self::MONTH);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the retrieve of the 1 month
     */
    public function testGetListThrowHttpException()
    {
        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willThrowException(new HttpException('HttpService Error'));
        $this->loggerMock->expects(self::at(0))
            ->method('debug')
            ->with('An error occurred during the retrieve of the 1 month');
        $this->loggerMock->expects(self::at(1))
            ->method('debug')
            ->with('HttpService Error');
        $this->service->getList(self::MONTH);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the retrieve of the 1 month
     */
    public function testGetListThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, self::PATH)
            ->willThrowException(new \Exception('HttpService Error'));
        $this->loggerMock->expects(self::at(0))
            ->method('debug')
            ->with('An error occurred during the retrieve of the 1 month');
        $this->loggerMock->expects(self::at(1))
            ->method('debug')
            ->with('HttpService Error');
        $this->service->getList(self::MONTH);
    }

    protected function Setup()
    {
        $this->clientMock = $this->createMock(HttpService::class);
        $this->loggerMock = $this->createMock(Logger::class);

        $this->service = new OpportunityMerlin(
            $this->clientMock,
            $this->loggerMock,
            self::USERNAME,
            self::PASSWORD,
            self::PATH
        );
    }
}
