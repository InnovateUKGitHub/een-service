<?php

use Common\Constant\EEN;

return [
    'elastic-search-indexes' => [
        EEN::ES_INDEX_OPPORTUNITY => [
            'index' => EEN::ES_INDEX_OPPORTUNITY,
            'body'  => [
                'mappings' => [
                    EEN::ES_TYPE_OPPORTUNITY => [
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
                                'type'                   => 'string',
                                'position_increment_gap' => 1,
                            ],
                            'description'        => [
                                'type'                   => 'string',
                                'position_increment_gap' => 2,
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
                                'type'   => 'string',
                                'fields' => [
                                    'raw' => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                ],
                            ],
                            'country'            => [
                                'type' => 'string',
                                'fields' => [
                                    'raw' => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                ],
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
        EEN::ES_INDEX_EVENT       => [
            'index' => EEN::ES_INDEX_EVENT,
            'body'  => [
                'mappings' => [
                    EEN::ES_TYPE_EVENT => [
                        'properties' => [
                            'title'                  => [
                                'type' => 'string',
                            ],
                            'start_date'             => [
                                'type' => 'date',
                            ],
                            'end_date'               => [
                                'type' => 'date',
                            ],
                            'closing_date'           => [
                                'type' => 'date',
                            ],
                            'url'                    => [
                                'type' => 'string',
                            ],
                            'description'            => [
                                'type' => 'string',
                            ],
                            'type'                   => [
                                'type' => 'string',
                            ],
                            'event_status'           => [
                                'type' => 'string',
                            ],
                            'host_organisation'      => [
                                'type' => 'string',
                            ],
                            'country_code'           => [
                                'type' => 'string',
                            ],
                            'country'                => [
                                'type' => 'string',
                            ],
                            'city'                   => [
                                'type' => 'string',
                            ],
                            'preliminary_text'       => [
                                'type' => 'string',
                            ],
                            'deadline'               => [
                                'type' => 'date',
                            ],
                            'location_city'          => [
                                'type' => 'string',
                            ],
                            'location_country'       => [
                                'type' => 'string',
                            ],
                            'location_name'          => [
                                'type' => 'string',
                            ],
                            'location_address'       => [
                                'type' => 'string',
                            ],
                            'location_phone'         => [
                                'type' => 'string',
                            ],
                            'location_fax'           => [
                                'type' => 'string',
                            ],
                            'location_contact_name'  => [
                                'type' => 'string',
                            ],
                            'location_contact_fax'   => [
                                'type' => 'string',
                            ],
                            'location_contact_phone' => [
                                'type' => 'string',
                            ],
                            'location_contact_email' => [
                                'type' => 'string',
                            ],
                            'created'                => [
                                'type' => 'date',
                            ],
                            'status'                 => [
                                'type' => 'string',
                            ],
                            'contact_name'           => [
                                'type' => 'string',
                            ],
                            'contact_phone'          => [
                                'type' => 'string',
                            ],
                            'contact_fax'            => [
                                'type' => 'string',
                            ],
                            'contact_email'          => [
                                'type' => 'string',
                            ],
                            'een_partner'            => [
                                'type' => 'string',
                            ],
                            'date_import'            => [
                                'type' => 'date',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
