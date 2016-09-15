<?php

namespace Search\Service;

use Elasticsearch\Client;

class QueryService
{
    /** @var Client */
    private $elasticSearch;
    /** @var string */
    private $must;
    /** @var string */
    private $should;

    /**
     * QueryService constructor.
     *
     * @param Client $elasticSearch
     */
    public function __construct(Client $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
        $this->must = [];
        $this->should = [];
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function exists($index)
    {
        return $this->elasticSearch->indices()->exists(['index' => $index]);
    }

    public function mustQueryString($fields, $values, $operator = 'AND')
    {
        $this->must[] = [
            'query_string' => [
                'fields' => $fields,
                'query'  => implode('* ' . $operator . ' ', $values) . '*',
            ],
        ];
    }

    public function mustRange($field, $value, $operator)
    {
        $this->must[] = [
            'range' => [
                $field => [
                    $operator => $value,
                ],
            ],
        ];
    }

    public function mustExist($field)
    {
        $this->must[] = [
            'exists' => [
                'field' => $field,
            ],
        ];
    }

    public function mustFuzzy($field, $search)
    {
        $this->must[] = [
            'fuzzy' => [
                $field => $search,
            ],
        ];
    }

    public function shouldFuzzy($field, $search)
    {
        $this->should[] = [
            'fuzzy' => [
                $field => $search,
            ],
        ];
    }

    public function shouldMatchPhrase($field, $search)
    {
        $this->should[] = [
            'match_phrase' => [
                $field => [
                    'query' => $search,
                    'slop'  => 50,
                ],
            ],
        ];
    }

    /**
     * @param array  $params
     * @param string $index
     * @param string $type
     *
     * @return array
     */
    public function search($params, $index, $type)
    {
        $query = [
            'index'   => $index,
            'type'    => $type,
            'from'    => $params['from'],
            'size'    => $params['size'],
            'body'    => [
                'query' => [
                    'bool' => [],
                ],
            ],
            '_source' => $params['source'],
        ];
        if (!empty($this->must)) {
            $query['body']['query']['bool']['must'] = $this->must;
        }
        if (!empty($this->should)) {
            $query['body']['query']['bool']['minimum_should_match'] = 1;
            $query['body']['query']['bool']['should'] = $this->should;
        }
        if (!empty($params['sort'])) {
            $query['body']['sort'] = $params['sort'];
        }

        return $this->convertResult($this->elasticSearch->search($query));
    }

    /**
     * @param array $results
     *
     * @return array
     */
    private function convertResult($results)
    {
        return [
            'total'   => $results['hits']['total'],
            'results' => $results['hits']['hits'],
        ];
    }

    /**
     * @param string $id
     * @param string $index
     * @param string $type
     *
     * @return array
     */
    public function getDocument($id, $index, $type)
    {
        $params = [
            'index' => $index,
            'type'  => $type,
            'id'    => $id,
        ];

        return $this->elasticSearch->get($params);
    }
}
