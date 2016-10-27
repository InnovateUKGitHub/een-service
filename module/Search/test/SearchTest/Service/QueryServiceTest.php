<?php

namespace SearchTest\Service;

use Common\Constant\EEN;
use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Search\Service\QueryService;

/**
 * @covers \Search\Service\QueryService
 * @covers \Search\Service\Query\MustQuery
 * @covers \Search\Service\Query\ShouldQuery
 */
class QueryServiceTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'index';
    const TYPE = 'type';
    const OPPORTUNITY_ID = 'myId';

    /** @var \PHPUnit_Framework_MockObject_MockObject|Client $elasticSearchMock */
    private $elasticSearchMock;

    public function testExist()
    {
        $service = new QueryService($this->elasticSearchMock);

        $mockIndices = $this->createMock(IndicesNamespace::class);
        $this->elasticSearchMock
            ->expects(self::once())
            ->method('indices')
            ->willReturn($mockIndices);

        $mockIndices->expects(self::once())
            ->method('exists')
            ->with(['index' => self::INDEX])
            ->willReturn(false);

        self::assertFalse($service->exists(self::INDEX));
    }

    public function testSearch()
    {
        $params = [
            'from'             => 0,
            'size'             => 10,
            'search'           => 'Some Search',
            'opportunity_type' => [
                'BO',
                'RD',
            ],
            'sort'             => ['title' => 'DESC'],
            'source'           => ['name', 'description'],
        ];

        $this->elasticSearchMock->expects(self::once())
            ->method('search')
            ->with([
                'index'   => self::INDEX,
                'type'    => self::TYPE,
                'from'    => $params['from'],
                'size'    => $params['size'],
                '_source' => $params['source'],
                'body'    => [
                    'query'     => [
                        'bool' => [
                            'must'                 => [
                                [
                                    'query_string' => [
                                        'fields' => ['title'],
                                        'query'  => 'Some* AND Search*',
                                    ],
                                ],
                            ],
                            'should'               => [
                                [
                                    'query_string' => [
                                        'fields' => ['title'],
                                        'query'  => 'Some~ AND Search~',
                                    ],

                                ],
                            ],
                            'minimum_should_match' => 1,
                        ],
                    ],
                    'highlight' => [
                        'pre_tags'            => ['<span>'],
                        'post_tags'           => ['</span>'],
                        'order'               => 'score',
                        'fields'              => [
                            'title' => [
                                'fragment_size'       => 0,
                                'number_of_fragments' => 0,
                                'highlight_query'     => [
                                    'bool' => [
                                        'must' => [
                                            'query_string' => [
                                                'fields' => ['title'],
                                                'query'  => 'Some* AND Search*',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'require_field_match' => false,
                    ],
                    'sort'      => ['title' => 'DESC'],
                ],
            ])
            ->willReturn([
                'hits' => [
                    'total' => 100,
                    'hits'  => [
                        [
                            'index'   => self::INDEX,
                            'type'    => self::TYPE,
                            '_source' => [
                                'name'        => 'Name',
                                'description' => 'Description',
                            ],
                        ],
                    ],
                ],
            ]);

        $service = new QueryService($this->elasticSearchMock);

        $service->mustQueryString(['title'], ['Some', 'Search']);
        $service->shouldFuzzy(['title'], ['Some', 'Search']);
        $service->highlight([
            'title' => [
                'fragment_size'       => 0,
                'number_of_fragments' => 0,
            ],
        ]);

        self::assertEquals(
            [
                'total'   => 100,
                'results' => [
                    [
                        'index'   => self::INDEX,
                        'type'    => self::TYPE,
                        '_source' => [
                            'name'        => 'Name',
                            'description' => 'Description',
                        ],
                    ],
                ],
            ],
            $service->search($params, self::INDEX, self::TYPE)
        );
    }

    public function testGetDocument()
    {
        $service = new QueryService($this->elasticSearchMock);

        $params = [
            'index' => self::INDEX,
            'type'  => self::TYPE,
            'id'    => self::OPPORTUNITY_ID,
        ];

        $this->elasticSearchMock
            ->expects(self::once())
            ->method('get')
            ->with($params)
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $service->getDocument(self::OPPORTUNITY_ID, self::INDEX, self::TYPE)
        );
    }

    public function testCount()
    {
        $service = new QueryService($this->elasticSearchMock);

        $params = [
            'index' => self::INDEX,
            'type'  => self::TYPE,
        ];

        $this->elasticSearchMock
            ->expects(self::once())
            ->method('count')
            ->with($params)
            ->willReturn(10);

        self::assertEquals(10, $service->count(self::INDEX, self::TYPE));
    }

    public function testGetCountryList()
    {
        $service = new QueryService($this->elasticSearchMock);

        $query = [
            'index'   => EEN::ES_INDEX_COUNTRY,
            'type'    => EEN::ES_TYPE_COUNTRY,
            'size'    => 1000,
            'body'    => [
                'sort' => [
                    ['name' => 'asc'],
                ],
            ],
            '_source' => ['name'],

        ];

        $results = [
            'hits' => [
                'hits' => [
                    [
                        '_id'     => 1,
                        '_source' => [
                            'name' => 'Name',
                        ],
                    ],
                ],
            ],
        ];

        $mockIndices = $this->createMock(IndicesNamespace::class);
        $this->elasticSearchMock
            ->expects(self::once())
            ->method('indices')
            ->willReturn($mockIndices);

        $mockIndices->expects(self::once())
            ->method('exists')
            ->with(['index' => EEN::ES_INDEX_COUNTRY])
            ->willReturn(true);

        $this->elasticSearchMock
            ->expects(self::once())
            ->method('search')
            ->with($query)
            ->willReturn($results);

        self::assertEquals([1 => 'Name'], $service->getCountryList());
    }

    public function testGetCountryListNoIndex()
    {
        $service = new QueryService($this->elasticSearchMock);

        $mockIndices = $this->createMock(IndicesNamespace::class);
        $this->elasticSearchMock
            ->expects(self::once())
            ->method('indices')
            ->willReturn($mockIndices);

        $mockIndices->expects(self::once())
            ->method('exists')
            ->with(['index' => EEN::ES_INDEX_COUNTRY])
            ->willReturn(false);

        self::assertEquals([], $service->getCountryList());
    }

    public function testFindTerm()
    {
        $service = new QueryService($this->elasticSearchMock);

        $query = [
            'index'   => EEN::ES_INDEX_OPPORTUNITY . EEN::ES_INDEX_WORDS,
            'type'    => EEN::ES_TYPE_COUNTRY . EEN::ES_INDEX_WORDS,
            'size'    => 10,
            'body'    => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'query_string' => [
                                'fields' => ['word'],
                                'query'  => 'A*',
                            ],
                        ],
                    ],
                ],
            ],
            '_source' => ['word'],
        ];

        $this->elasticSearchMock
            ->expects(self::once())
            ->method('search')
            ->with($query)
            ->willReturn([1 => 'Name']);

        self::assertEquals([1 => 'Name'], $service->findTerm('A', 10));
    }

    protected function Setup()
    {
        $this->elasticSearchMock = $this->createMock(Client::class);
    }
}
