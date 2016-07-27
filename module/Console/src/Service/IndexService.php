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
                        ],
                    ],
                ],
            ],
        ];
        $this->elasticSearch->indices()->create($params);
    }

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
}