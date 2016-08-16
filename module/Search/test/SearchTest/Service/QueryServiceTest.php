<?php

namespace ConsoleTest\Service;

use Elasticsearch\Client;
use Search\Service\QueryService;

/**
 * @covers Search\Service\QueryService
 */
class QueryServiceTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'index';
    const TYPE = 'type';

    public function testSearch()
    {
        $params = [
            'from'   => 0,
            'size'   => 10,
            'search' => 'Some Search',
            'sort'   => [
                ['date.timestamp' => 'desc'],
            ],
            'source' => ['name', 'description'],
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|Client $elasticSearchMock */
        $elasticSearchMock = $this->createMock(Client::class);
        $elasticSearchMock->expects(self::once())
            ->method('search')
            ->with([
                'index' => self::INDEX,
                'type'  => self::TYPE,
                'from'  => $params['from'],
                'size'  => $params['size'],
                'body'  => [
                    'query'   => [
                        'bool' => [
                            'should' => [
                                'query_string' => [
                                    'fields' => ['title', 'summary', 'description'],
                                    'query'  => '*Some* AND *Search*',
                                ],
                            ],
                        ],
                    ],
                    'sort'    => $params['sort'],
                    '_source' => $params['source'],
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

        $service = new QueryService($elasticSearchMock);

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
}
