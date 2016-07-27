<?php

namespace ConsoleTest\Service;

use Console\Service\DeleteService;
use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;

/**
 * @covers Console\Service\DeleteService
 */
class DeleteServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testDelete()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Client $serviceMock */
        $serviceMock = self::getMock(Client::class, [], [], '', false);
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndicesNamespace $indicesMock */
        $indicesMock = self::getMock(IndicesNamespace::class, [], [], '', false);

        $service = new DeleteService($serviceMock);

        $serviceMock
            ->expects(self::once())
            ->method('indices')
            ->willReturn($indicesMock);
        $indicesMock
            ->expects(self::once())
            ->method('delete')
            ->with(['index' => 'index'])
            ->willReturn(['success' => true]);

        self::assertEquals(['success' => true], $service->delete('index'));
    }
}
