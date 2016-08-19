<?php

namespace ConsoleTest\Service;

use Console\Factory\Service\ImportServiceFactory;
use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;

/**
 * @covers Console\Service\ImportService
 */
class ImportServiceTest extends \PHPUnit_Framework_TestCase
{
    const SINCE = 1;

    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexServiceMock */
    private $indexServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|HttpService $httpServiceMock */
    private $httpServiceMock;
    /** @var ImportService $service */
    private $service;

    protected function Setup()
    {
        $config = [
            ImportServiceFactory::SERVER    => 'een.ec.europa.eu',
            ImportServiceFactory::PORT      => '80',
            ImportService::PATH_GET_PROFILE => 'tools/services/podv6/QueryService.svc/GetProfiles?',
            ImportService::USERNAME         => '%%MERLIN_GLOBAL_USERNAME%%',
            ImportService::PASSWORD         => '%%MERLIN_GLOBAL_PASSWORD%%',
        ];

        $this->indexServiceMock = $this->createMock(IndexService::class);
        $this->httpServiceMock = $this->createMock(HttpService::class);

        $this->service = new ImportService(
            $this->httpServiceMock,
            $this->indexServiceMock,
            $config
        );
    }

    public function testDelete()
    {
        $this->indexServiceMock->expects(self::once())
            ->method('getAll')
            ->willReturn(['hits' => ['hits' => []]]);
        $this->service->delete(self::SINCE);
    }
}
