<?php
namespace Console\Service;

use Elasticsearch\Client;

class IndexService
{
    const ES_INDEX_OPPORTUNITY = 'opportunity';
    const ES_INDEX_EVENT = 'event';
    const ES_TYPE_OPPORTUNITY = 'opportunity';
    const ES_TYPE_EVENT = 'event';
    /** @var Client */
    private $elasticSearch;

    /**
     * @param Client $elasticSearch
     */
    public function __construct(Client $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function createIndex($index)
    {
        if ($this->elasticSearch->indices()->exists(['index' => $index]) === true) {
            return true;
        }
        switch ($index) {
            case self::ES_INDEX_OPPORTUNITY:
                $this->createOpportunityIndex();
                break;
            case self::ES_INDEX_EVENT:
                $this->createEventIndex();
                break;
        }

        return false;
    }

    /**
     * Function to create the mapping of the opportunity index
     */
    private function createOpportunityIndex()
    {
        $params = [
            'index' => self::ES_INDEX_OPPORTUNITY,
            'body'  => [
                'mappings' => [
                    self::ES_TYPE_OPPORTUNITY => [
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
        ];
        $this->elasticSearch->indices()->create($params);
    }

    /**
     * Function to create the mapping of the event index
     */
    private function createEventIndex()
    {
        $params = [
            'index' => self::ES_INDEX_EVENT,
            'body'  => [
                'mappings' => [
                    self::ES_TYPE_EVENT => [
                        'properties' => [
                            'id'          => [
                                'type' => 'long',
                            ],
                            'name'        => [
                                'type' => 'string',
                            ],
                            'type'        => [
                                'type' => 'string',
                            ],
                            'place'       => [
                                'type' => 'string',
                            ],
                            'address'     => [
                                'type' => 'string',
                            ],
                            'date_from'   => [
                                'type'   => 'date',
                                'format' => 'strict_date_optional_time||epoch_millis',
                            ],
                            'date_to'     => [
                                'type'   => 'date',
                                'format' => 'strict_date_optional_time||epoch_millis',
                            ],
                            'description' => [
                                'type' => 'string',
                            ],
                            'attendee'    => [
                                'type' => 'string',
                            ],
                            'agenda'      => [
                                'type' => 'string',
                            ],
                            'cost'        => [
                                'type' => 'string',
                            ],
                            'topics'      => [
                                'type' => 'string',
                            ],
                            'latitude'    => [
                                'type' => 'float',
                            ],
                            'longitude'   => [
                                'type' => 'float',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $this->elasticSearch->indices()->create($params);
    }

    /**
     * @param $values
     * @param $id
     * @param $index
     * @param $type
     *
     * @return array
     */
    public function index($values, $id, $index, $type)
    {
        $params = [
            'body'  => $values,
            'index' => $index,
            'type'  => $type,
            'id'    => $id,
        ];

        return $this->elasticSearch->index($params);
    }

    /**
     * @param array $results
     * @param int   $from
     * @param int   $size
     *
     * @return array|null
     */
    public function getAll($results = [], $from = 0, $size = 100)
    {
        $query = [
            'index'   => self::ES_INDEX_OPPORTUNITY,
            'type'    => self::ES_TYPE_OPPORTUNITY,
            'from'    => $from,
            'size'    => $size,
            '_source' => ['id', 'date', 'deadline', 'date_import'],
        ];

        try {
            $tmp = $this->elasticSearch->search($query);
            if (empty($results)) {
                $results = $tmp;
            } else {
                $results['hits']['hits'] = array_merge($results['hits']['hits'], $tmp['hits']['hits']);
            }

            if (count($tmp['hits']['hits']) > 0) {
                return $this->getAll($results, $from + $size);
            }

            return $results;
        } catch (\Exception $e) {
            echo "An error occurred during the removal of old documents\n";
            echo $e->getMessage() . "\n";
        }

        return null;
    }

    /**
     * @param array $params
     */
    public function delete($params)
    {
        try {
            $this->elasticSearch->bulk($params);
        } catch (\Exception $e) {
            echo "An error occurred during the removal of the documents\n";
            echo $e->getMessage() . "\n";
        }
    }
}