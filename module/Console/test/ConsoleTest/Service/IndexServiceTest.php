<?php

namespace ConsoleTest\Service;

use Console\Service\IndexService;
use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;

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
    /** @var IndexService $service */
    private $service;

    protected function Setup()
    {
        $this->clientMock = $this->createMock(Client::class);

        $this->service = new IndexService($this->clientMock);
    }

    public function testCreateIndex()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::exactly(2))
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => IndexService::ES_INDEX_OPPORTUNITY])
            ->willReturn(false);

        $indicesMock->expects(self::once())
            ->method('create')
            ->with([
                'index' => IndexService::ES_INDEX_OPPORTUNITY,
                'body'  => [
                    'mappings' => [
                        IndexService::ES_TYPE_OPPORTUNITY => [
                            'properties' => [
                                'id'                 => [
                                    'type' => 'string',
                                ],
                                'type'               => [
                                    'type' => 'string',
                                ],
                                'title'              => [
                                    'type' => 'string',
                                ],
                                'summary'            => [
                                    'type' => 'string',
                                ],
                                'description'        => [
                                    'type' => 'string',
                                ],
                                'partner_expertise'  => [
                                    'type' => 'string',
                                ],
                                'stage'              => [
                                    'type' => 'string',
                                ],
                                'ipr'                => [
                                    'type' => 'string',
                                ],
                                'ipr_comment'        => [
                                    'type' => 'string',
                                ],
                                'country_code'       => [
                                    'type' => 'string',
                                ],
                                'country'            => [
                                    'type' => 'string',
                                ],
                                'date'               => [
                                    'type' => 'date',
                                ],
                                'deadline'           => [
                                    'type' => 'date',
                                ],
                                'partnership_sought' => [
                                    'type' => 'string',
                                ],
                                'industries'         => [
                                    'type' => 'string',
                                ],
                                'technologies'       => [
                                    'type' => 'string',
                                ],
                                'commercials'        => [
                                    'type' => 'string',
                                ],
                                'markets'            => [
                                    'type' => 'string',
                                ],
                                'eoi'                => [
                                    'type' => 'boolean',
                                ],
                                'advantage'          => [
                                    'type' => 'string',
                                ],
                                'date_import'        => [
                                    'type' => 'date',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

        $this->service->createIndex(IndexService::ES_INDEX_OPPORTUNITY);
    }

    public function testCreateIndexAlreadyCreated()
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->clientMock->expects(self::once())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(self::once())
            ->method('exists')
            ->with(['index' => IndexService::ES_INDEX_OPPORTUNITY])
            ->willReturn(true);

        $this->service->createIndex(IndexService::ES_INDEX_OPPORTUNITY);
    }

    public function testIndex()
    {
        $this->clientMock->expects(self::once())
            ->method('index')
            ->with([
                'body'  => 'body',
                'index' => IndexService::ES_INDEX_OPPORTUNITY,
                'type'  => IndexService::ES_TYPE_OPPORTUNITY,
                'id'    => 1,
            ])
            ->willReturn(true);

        self::assertTrue(
            $this->service->index(
                'body',
                1,
                IndexService::ES_INDEX_OPPORTUNITY,
                IndexService::ES_TYPE_OPPORTUNITY
            )
        );
    }

    public function testGetAll()
    {
        $this->clientMock->expects(self::at(0))
            ->method('search')
            ->with([
                'index'   => IndexService::ES_INDEX_OPPORTUNITY,
                'type'    => IndexService::ES_TYPE_OPPORTUNITY,
                'from'    => 0,
                'size'    => 100,
                '_source' => ['id', 'date', 'deadline', 'date_import'],
            ])
            ->willReturn(['hits' => ['hits' => ['A hit']]]);

        $this->clientMock->expects(self::at(1))
            ->method('search')
            ->with([
                'index'   => IndexService::ES_INDEX_OPPORTUNITY,
                'type'    => IndexService::ES_TYPE_OPPORTUNITY,
                'from'    => 100,
                'size'    => 100,
                '_source' => ['id', 'date', 'deadline', 'date_import'],
            ])
            ->willReturn(['hits' => ['hits' => []]]);

        self::assertEquals(['hits' => ['hits' => ['A hit']]], $this->service->getAll());
    }

    public function testGetAllThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('search')
            ->with([
                'index'   => IndexService::ES_INDEX_OPPORTUNITY,
                'type'    => IndexService::ES_TYPE_OPPORTUNITY,
                'from'    => 0,
                'size'    => 100,
                '_source' => ['id', 'date', 'deadline', 'date_import'],
            ])
            ->willThrowException(new \Exception());

        self::assertNull($this->service->getAll());
    }

    public function testDelete()
    {
        $this->clientMock->expects(self::once())
            ->method('bulk')
            ->with('params');

        self::assertTrue($this->service->delete('params'));
    }

    public function testDeleteThrowException()
    {
        $this->clientMock->expects(self::once())
            ->method('bulk')
            ->with('params')
            ->willThrowException(new \Exception());

        self::assertFalse($this->service->delete('params'));
    }
}
