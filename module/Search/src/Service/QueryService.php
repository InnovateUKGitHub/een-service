<?php

namespace Search\Service;

use Elasticsearch\Client;

class QueryService
{
    /** @var Client */
    private $elasticSearch;
    /** @var array */
    private $must;
    /** @var array */
    private $should;
    /** @var array */
    private $highlight;

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

    public function mustFuzzy($fields, $values, $operator = 'AND')
    {
        $this->must[] = [
            'query_string' => [
                'fields' => $fields,
                'query'  => implode('~ ' . $operator . ' ', $values) . '~',
            ],
        ];
    }

    public function shouldFuzzy($fields, $values, $operator = 'AND')
    {
        $this->should[] = [
            'query_string' => [
                'fields' => $fields,
                'query'  => implode('~ ' . $operator . ' ', $values) . '~',
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

    public function mustMatchPhrase($fields, $values)
    {
        $this->must[] = [
            'query_string' => [
                'fields'      => $fields,
                'query'       => implode('~ ', $values) . '~',
                'phrase_slop' => 50,
            ],
        ];
    }

    public function highlight($fields, $html = 'span')
    {
        $this->highlight = [
            'pre_tags'            => ['<' . $html . '>'],
            'post_tags'           => ['</' . $html . '>'],
            'encoder'             => 'html',
            'fields'              => [
                'title'   => ['force_source' => true,],
                'summary' => ['force_source' => true,],
            ],
            'require_field_match' => false,
        ];

        foreach ($fields as $field) {
            $this->highlight['fields'][$field] = ['force_source' => true];
        }
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
        if (!empty($this->highlight)) {
            $query['body']['highlight'] = $this->highlight;
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

    public function getCountryList()
    {
        $query = [
            'index' => ES_INDEX_OPPORTUNITY,
            'type'  => ES_TYPE_OPPORTUNITY,
            'size'  => 0,
            'body'  => [
                'aggs' => [
                    'country'      => [
                        'terms' => [
                            'field' => 'country.raw',
                        ],
                    ],
                    'country_code' => [
                        'terms' => [
                            'field' => 'country_code.raw',
                        ],
                    ],
                ],
            ],
        ];

        return $this->filterAggregation($this->elasticSearch->search($query));
    }

    private function filterAggregation($aggregations)
    {
        $result = [];

        $i = 0;
        foreach ($aggregations['aggregations']['country_code']['buckets'] as $countryCode) {
            $result[$countryCode['key']] = $aggregations['aggregations']['country']['buckets'][$i++]['key'];
        }

        asort($result);

        return $result;
    }
}
