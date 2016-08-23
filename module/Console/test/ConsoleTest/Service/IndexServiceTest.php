<?php

namespace ConsoleTest\Service;

use Console\Service\IndexService;
use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Zend\Log\Logger;

/**
 * @covers Console\Service\IndexService
 */
class IndexServiceTest extends \PHPUnit_Framework_TestCase
{
    const SINCE = 1;

    const MONTH = 1;

    const PATH = 'tools/services/podv6/QueryService.svc/GetProfiles?';

    /** @var \PHPUnit_Framework_MockObject_MockObject|Client $clientMock */
    private $clientMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Logger $loggerMock */
    private $loggerMock;
    /** @var IndexService $service */
    private $service;

    public function testCreateIndex()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::exactly(2))
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => ES_INDEX_OPPORTUNITY])
            ->willReturn(false);

        $indicesMock->expects(self::once())
            ->method('create')
            ->with([
                'Some Index Information' => '',
            ]);

        $this->service->createIndex(ES_INDEX_OPPORTUNITY);
    }

    public function testCreateIndexAlreadyCreated()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::once())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => ES_INDEX_OPPORTUNITY])
            ->willReturn(true);

        $this->service->createIndex(ES_INDEX_OPPORTUNITY);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the creation of the index
     */
    public function testExistIndexThrowException()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::once())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => ES_INDEX_OPPORTUNITY])
            ->willThrowException(new \Exception());

        $this->service->createIndex(ES_INDEX_OPPORTUNITY);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the creation of the index
     */
    public function testCreateIndexThrowException()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::exactly(2))
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => ES_INDEX_OPPORTUNITY])
            ->willReturn(false);

        $indicesMock->expects(self::once())
            ->method('create')
            ->with([
                'Some Index Information' => '',
            ])
            ->willThrowException(new \Exception());

        $this->service->createIndex(ES_INDEX_OPPORTUNITY);
    }

    public function testIndex()
    {
        $this->clientMock->expects(self::once())
            ->method('index')
            ->with([
                'body'  => 'body',
                'index' => ES_INDEX_OPPORTUNITY,
                'type'  => ES_TYPE_OPPORTUNITY,
                'id'    => 1,
            ])
            ->willReturn(true);

        self::assertTrue(
            $this->service->index(
                'body',
                1,
                ES_INDEX_OPPORTUNITY,
                ES_TYPE_OPPORTUNITY
            )
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the import of a document
     */
    public function testIndexThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('index')
            ->with([
                'body'  => 'body',
                'index' => ES_INDEX_OPPORTUNITY,
                'type'  => ES_TYPE_OPPORTUNITY,
                'id'    => 1,
            ])
            ->willThrowException(new \Exception());

        $this->service->index(
            'body',
            1,
            ES_INDEX_OPPORTUNITY,
            ES_TYPE_OPPORTUNITY
        );
    }

    public function testGetAll()
    {
        $this->clientMock->expects(self::at(0))
            ->method('search')
            ->with([
                'index'   => ES_INDEX_OPPORTUNITY,
                'type'    => ES_TYPE_OPPORTUNITY,
                'from'    => 0,
                'size'    => 100,
                '_source' => ['id', 'date', 'deadline', 'date_import'],
            ])
            ->willReturn(['hits' => ['hits' => ['A hit']]]);

        $this->clientMock->expects(self::at(1))
            ->method('search')
            ->with([
                'index'   => ES_INDEX_OPPORTUNITY,
                'type'    => ES_TYPE_OPPORTUNITY,
                'from'    => 100,
                'size'    => 100,
                '_source' => ['id', 'date', 'deadline', 'date_import'],
            ])
            ->willReturn(['hits' => ['hits' => []]]);

        self::assertEquals(['hits' => ['hits' => ['A hit']]], $this->service->getAll());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the removal of old documents
     */
    public function testGetAllThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('search')
            ->with([
                'index'   => ES_INDEX_OPPORTUNITY,
                'type'    => ES_TYPE_OPPORTUNITY,
                'from'    => 0,
                'size'    => 100,
                '_source' => ['id', 'date', 'deadline', 'date_import'],
            ])
            ->willThrowException(new \Exception());

        $this->service->getAll();
    }

    public function testDelete()
    {
        $this->clientMock->expects(self::once())
            ->method('bulk')
            ->with('params');

        self::assertTrue($this->service->delete('params'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred during the removal of old documents
     */
    public function testDeleteThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('bulk')
            ->with('params')
            ->willThrowException(new \Exception());

        $this->service->delete('params');
    }

    protected function Setup()
    {
        $config = [
            ES_INDEX_OPPORTUNITY => [
                'Some Index Information' => '',
            ],
        ];
        $this->clientMock = $this->createMock(Client::class);
        $this->loggerMock = $this->createMock(Logger::class);

        $this->service = new IndexService($this->clientMock, $this->loggerMock, $config);
    }
}