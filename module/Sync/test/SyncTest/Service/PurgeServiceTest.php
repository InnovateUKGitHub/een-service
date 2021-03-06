<?php

namespace SyncTest\Service;

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Sync\Service\PurgeService;

/**
 * @covers \Sync\Service\PurgeService
 */
class PurgeServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testDelete()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Client $serviceMock */
        $serviceMock = $this->createMock(Client::class);
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndicesNamespace $indicesMock */
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $service = new PurgeService($serviceMock);

        $serviceMock
            ->expects(self::once())
            ->method('indices')
            ->willReturn($indicesMock);
        $indicesMock
            ->expects(self::once())
            ->method('delete')
            ->with(['index' => 'index'])
            ->willReturn(['success' => true]);

        self::assertEquals(['success' => true], $service->purge('index'));
    }
}
