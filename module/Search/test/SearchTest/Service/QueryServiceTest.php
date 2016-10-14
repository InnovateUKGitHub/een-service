<?php

namespace SearchTest\Service;

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Search\Service\QueryService;

/**
 * @covers \Search\Service\QueryService
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

    protected function Setup()
    {
        $this->elasticSearchMock = $this->createMock(Client::class);
    }
}
