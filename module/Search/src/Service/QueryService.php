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
            'encoder'             => 'html',
            'order'               => 'score',
            'fields'              => [],
            'require_field_match' => false,
        ];

        foreach ($fields as $field => $args) {
            $this->highlight['fields'][$field] = [
                'fragment_size'       => $args['fragment_size'],
                'number_of_fragments' => $args['number_of_fragments'],
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
     *
     * @return array
     */
    private function buildQuery($index, $type)
    {
        $query = [
            'index' => $index,
            'type'  => $type,
            'body'  => [
                'query' => [
                    'bool' => [],
                ],
            ],
        ];

        if (!empty($this->must)) {
            $query['body']['query']['bool']['must'] = $this->must;
        }
        if (!empty($this->should)) {
            $query['body']['query']['bool']['minimum_should_match'] = 1;
            $query['body']['query']['bool']['should'] = $this->should;
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
        $query = $this->buildQuery($index, $type);

        $query['from'] = $params['from'];
        $query['size'] = $params['size'];
        $query['_source'] = $params['source'];
        if (!empty($this->highlight)) {
            $query['body']['highlight'] = $this->highlight;
        }
        if (!empty($params['sort'])) {
            $query['body']['sort'] = $params['sort'];
        }

        return $this->convertResult($this->elastic->search($query));
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

        return $this->elastic->get($params);
    }

    /**
     * @return array
     */
    public function getCountryList()
    {
        if ($this->exists(EEN::ES_INDEX_COUNTRY) === false) {
            return ['total' => 0];
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

    private function convertToAssociatedArray($results)
    {
        $response = [];
        foreach ($results['hits']['hits'] as $result) {
            $response[$result['_id']] = $result['_source']['name'];
        }
        return $response;
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
}
