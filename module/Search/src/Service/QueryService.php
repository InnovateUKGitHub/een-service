<?php

namespace Search\Service;

use Common\Constant\EEN;
use Elasticsearch\Client;
use Search\Service\Query\MustQuery;

class QueryService extends MustQuery
{
    /** @var Client */
    private $elastic;
    /** @var array */
    private $highlight = [];

    /**
     * QueryService constructor.
     *
     * @param Client $elastic
     */
    public function __construct(Client $elastic)
    {
        $this->elastic = $elastic;
    }

    /**
     * @param array  $fields
     * @param string $html
     */
    public function highlight($fields, $html = 'span')
    {
        $this->highlight = [
            'pre_tags'            => ['<' . $html . '>'],
            'post_tags'           => ['</' . $html . '>'],
            'order'               => 'score',
            'fields'              => [],
            'require_field_match' => false,
        ];

        foreach ($fields as $field => $args) {
            $this->highlight['fields'][$field] = [
                'fragment_size'       => $args['fragment_size'],
                'number_of_fragments' => $args['number_of_fragments'],
                'highlight_query'     => [
                    'bool' => [
                        'must' => $this->must[0],
                    ],
                ],
            ];
        }
    }

    /**
     * @param string $index
     * @param string $type
     *
     * @return array
     */
    public function count($index, $type)
    {
        $query = $this->buildQuery($index, $type);

        return $this->elastic->count($query);
    }

    /**
     * @param string $index
     * @param string $type
     * @param array  $params
     *
     * @return array
     */
    private function buildQuery($index, $type, $params = null)
    {
        $query = [
            'index' => $index,
            'type'  => $type,
        ];

        if (!empty($this->must)) {
            $query['body']['query']['bool']['must'] = $this->must;
        }
        if (!empty($this->should)) {
            $query['body']['query']['bool']['minimum_should_match'] = 1;
            $query['body']['query']['bool']['should'] = $this->should;
        }

        if ($params !== null) {
            $query['from'] = $params['from'];
            $query['size'] = $params['size'];
            $query['_source'] = $params['source'];
            if (!empty($this->highlight)) {
                $query['body']['highlight'] = $this->highlight;
            }
            if (!empty($params['sort'])) {
                $query['body']['sort'] = $params['sort'];
            }

            $query['body']['aggs'] = [
                'types' => [
                    'terms' => [
                        'field' => 'type',
                    ]
                ],
                'autocomplete' => [
                    "terms" => [
                        'field' => 'autocomplete',
                        'order' => [
                            '_count' => 'desc',
                        ],
                        'include' => [
                            'pattern' => $params['search'].".*"
                        ]
                    ]
                ]
            ];
        }

        return $query;
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
        $query = $this->buildQuery($index, $type, $params);

        $aggResult = null;
        if (empty($params['opportunity_type']) === false) {
            $aggResult = $this->elastic->search($query)['aggregations'];
            $this->mustQueryString(['type'], $params['opportunity_type'], 'OR');
            $query = $this->buildQuery($index, $type, $params);
        }

        return $this->convertResult($this->elastic->search($query), $aggResult);
    }

    /**
     * @param array $results
     * @param array $aggResult
     *
     * @return array
     */
    private function convertResult($results, $aggResult)
    {
        return [
            'total'        => $results['hits']['total'],
            'results'      => $results['hits']['hits'],
            'aggregations' => $aggResult !== null ? $aggResult : $results['aggregations'],
            'raw_results'  => $results,
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

        return $this->elastic->get($params);
    }

    /**
     * @return array
     */
    public function getCountryList()
    {
        if ($this->exists(EEN::ES_INDEX_COUNTRY) === false) {
            return [];
        }

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

        return $this->convertToAssociatedArray($this->elastic->search($query));
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function exists($index)
    {
        return $this->elastic->indices()->exists(['index' => $index]);
    }

    /**
     * @param array $results
     *
     * @return array
     */
    private function convertToAssociatedArray($results)
    {
        $response = [];
        foreach ($results['hits']['hits'] as $result) {
            $response[$result['_id']] = $result['_source']['name'];
        }

        return $response;
    }

    /**
     * @param string $search
     * @param int    $size
     *
     * @return array
     */
    public function findTerm($search, $size)
    {
        $query = [
            'index'   => EEN::ES_INDEX_OPPORTUNITY . EEN::ES_INDEX_WORDS,
            'type'    => EEN::ES_TYPE_COUNTRY . EEN::ES_INDEX_WORDS,
            'size'    => $size,
            'body'    => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'query_string' => [
                                'fields' => ['word'],
                                'query'  => $search . '*',
                            ],
                        ],
                    ],
                ],
            ],
            '_source' => ['word'],
        ];

        return $this->elastic->search($query);
    }
}
