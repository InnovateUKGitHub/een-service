<?php

return [
    'EventStartDate'                  => '',
    'EventEndDate'                    => '',
    'EventTitle'                      => '',
    'EventClosingDate'                => '',
    'ContactAttributes'               => '',
    'Description'                     => '',
    'EventType'                       => [
        'required' => false,
    ],
    'EventStyle'                      => [
        'required' => false,
    ],
    'EventStatus'                     => '',
    'HostOrganisation'                => '',
    'CountryISO'                      => '',
    'Country'                         => '',
    'City'                            => '',
    'Preliminarytext'                 => '',
    'DeadlineForRegistering'          => '',
    'LocationDetailsCity'             => '',
    'LocationDetailsCountry'          => [
        'required' => false,
    ],
    'LocationDetailsName'             => '',
    'LocationDetailsEventAddress'     => '',
    'LocationDetailsContactTelephone' => '',
    'LocationDetailsContactFax'       => '',
    'LocationDetailsWebPage'          => '',
    'LocationContactName'             => '',
    'LocationContactFax'              => '',
    'LocationContactTelephone'        => '',
    'LocationContactEmail'          => [
        'required' => false,
    ],
    'Created'                         => '',
    'Status'                          => '',
    'keyword'                         => [
        'technologies' => [
            'technology' => [
                'required' => false,
                'label'    => '',
            ],
        ],
        'naces'        => [
            'nace' => [
                'required' => false,
                'label'    => '',
            ],
        ],
        'markets'      => [
            'market' => [
                'required' => false,
                'label'    => '',
            ],
        ],
    ],
    'Coorganisers'                    => [
        'Coorganiser' => [
            'required' => false,
        ],
    ],
    'ContactName'                     => '',
    'ContactTelephone'                => '',
    'ContactFax'                      => '',
    'ContactEmail'                    => '',
    'NameOfEENPartner'                => '',
];
