<?php

namespace SyncTest\Service;

use Common\Constant\EEN;
use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Sync\Service\IndexService;
use Zend\Log\Logger;

/**
 * @covers \Sync\Service\IndexService
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
            ->with(['index' => EEN::ES_INDEX_OPPORTUNITY])
            ->willReturn(false);

        $indicesMock->expects(self::once())
            ->method('create')
            ->with([
                'Some Index Information' => '',
            ]);

        $this->service->createIndex(EEN::ES_INDEX_OPPORTUNITY);
    }

    public function testCreateIndexAlreadyCreated()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::once())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => EEN::ES_INDEX_OPPORTUNITY])
            ->willReturn(true);

        $this->service->createIndex(EEN::ES_INDEX_OPPORTUNITY);
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
            ->with(['index' => EEN::ES_INDEX_OPPORTUNITY])
            ->willThrowException(new \Exception());

        $this->service->createIndex(EEN::ES_INDEX_OPPORTUNITY);
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
            ->with(['index' => EEN::ES_INDEX_OPPORTUNITY])
            ->willReturn(false);

        $indicesMock->expects(self::once())
            ->method('create')
            ->with([
                'Some Index Information' => '',
            ])
            ->willThrowException(new \Exception('An error occurred during the creation of the index'));

        $this->service->createIndex(EEN::ES_INDEX_OPPORTUNITY);
    }

    public function testIndex()
    {
        $this->clientMock->expects(self::once())
            ->method('index')
            ->with([
                'body'  => 'body',
                'index' => EEN::ES_INDEX_OPPORTUNITY,
                'type'  => EEN::ES_TYPE_OPPORTUNITY,
                'id'    => 1,
            ])
            ->willReturn(true);

        self::assertTrue(
            $this->service->index(
                'body',
                1,
                EEN::ES_INDEX_OPPORTUNITY,
                EEN::ES_TYPE_OPPORTUNITY
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
                'index' => EEN::ES_INDEX_OPPORTUNITY,
                'type'  => EEN::ES_TYPE_OPPORTUNITY,
                'id'    => 1,
            ])
            ->willThrowException(new \Exception('An error occurred during the import of a document'));

        $this->service->index(
            'body',
            1,
            EEN::ES_INDEX_OPPORTUNITY,
            EEN::ES_TYPE_OPPORTUNITY
        );
    }

    public function testGetAll()
    {
        $this->clientMock->expects(self::at(0))
            ->method('search')
            ->with([
                'index' => EEN::ES_INDEX_OPPORTUNITY,
                'type'  => EEN::ES_TYPE_OPPORTUNITY,
                'from'  => 0,
                'size'  => 100,
                'body'  => [
                    'query' => [
                        'range' => [
                            'date_import' => [
                                'lt' => '20161012',
                            ],
                        ],
                    ],
                ],
            ])
            ->willReturn(['hits' => ['hits' => ['A hit']]]);

        $this->clientMock->expects(self::at(1))
            ->method('search')
            ->with([
                'index' => EEN::ES_INDEX_OPPORTUNITY,
                'type'  => EEN::ES_TYPE_OPPORTUNITY,
                'from'  => 100,
                'size'  => 100,
                'body'  => [
                    'query' => [
                        'range' => [
                            'date_import' => [
                                'lt' => '20161012',
                            ],
                        ],
                    ],
                ],
            ])
            ->willReturn(['hits' => ['hits' => []]]);

        self::assertEquals(
            ['hits' => ['hits' => ['A hit']]],
            $this->service->getOutOfDateData(
                EEN::ES_INDEX_OPPORTUNITY,
                EEN::ES_TYPE_OPPORTUNITY,
                '20161012'
            )
        );
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
                'index' => EEN::ES_INDEX_OPPORTUNITY,
                'type'  => EEN::ES_TYPE_OPPORTUNITY,
                'from'  => 0,
                'size'  => 100,
                'body'  => [
                    'query' => [
                        'range' => [
                            'date_import' => [
                                'lt' => '20161012',
                            ],
                        ],
                    ],
                ],
            ])
            ->willThrowException(new \Exception('An error occurred during the removal of old documents'));

        $this->service->getOutOfDateData(
            EEN::ES_INDEX_OPPORTUNITY,
            EEN::ES_TYPE_OPPORTUNITY,
            '20161012'
        );
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
            EEN::ES_INDEX_OPPORTUNITY => [
                'Some Index Information' => '',
            ],
        ];
        $this->clientMock = $this->createMock(Client::class);
        $this->loggerMock = $this->createMock(Logger::class);

        $this->service = new IndexService($this->clientMock, $this->loggerMock, $config);
    }
}
