<?php

namespace Search\Service;

use Elasticsearch\Client;

class QueryService
{
    /** @var Client */
    private $elasticSearch;

    /**
     * ElasticSearchService constructor.
     */
    public function __construct(Client $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    public function exists($index)
    {
        return $this->elasticSearch->indices()->exists(['index' => $index]);
    }

    public function search($params, $index, $type)
    {
        $query = [
            'index' => $index,
            'type'  => $type,
            'from'  => $params['from'],
            'size'  => $params['size'],
            'body'  => [
                'query'   => [
                    'bool' => [
                        'must' => [
                            'query_string' => [
                                'default_field' => 'title',
                                'query'         => '*' . $params['search'] . '*',
                            ],
                        ],
                    ],
                ],
                'sort'    => $params['sort'],
                '_source' => $params['source'],
            ],
        ];

        return $this->convertResult($this->elasticSearch->search($query));
    }

    public function getDocument($id, $index, $type)
    {
        $params = [
            'index' => $index,
            'type'  => $type,
            'id'  => $id
        ];

        return $this->elasticSearch->get($params);
    }

    public function convertResult($results)
    {
        return [
            'total'   => $results['hits']['total'],
            'results' => $results['hits']['hits'],
        ];
    }
}
