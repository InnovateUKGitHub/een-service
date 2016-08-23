<?php

const ES_INDEX_OPPORTUNITY = 'opportunity';
const ES_TYPE_OPPORTUNITY = 'opportunity';

return [
    'elastic-search-indexes' => [
        ES_INDEX_OPPORTUNITY => [
            'index' => ES_INDEX_OPPORTUNITY,
            'body'  => [
                'mappings' => [
                    ES_TYPE_OPPORTUNITY => [
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
                            'date_create'        => [
                                'type' => 'date',
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
        ],
    ],
];
