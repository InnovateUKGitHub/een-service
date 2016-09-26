<?php

use Common\Constant\EEN;

return [
    'elastic-search-indexes' => [
        EEN::ES_INDEX_OPPORTUNITY => [
            'index' => EEN::ES_INDEX_OPPORTUNITY,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0,
                    'analysis'           => [
                        'analyzer' => [
                            'my_analyzer' => [
                                'type'      => 'standard',
                                'stopwords' => ['a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'among', 'amongst', 'amoungst', 'amount', 'an', 'and', 'another', 'any', 'anyhow', 'anyone', 'anything', 'anyway', 'anywhere', 'are', 'around', 'as', 'at', 'back', 'be', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom', 'but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven', 'else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 'part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the'],
                            ],
                        ],
                    ],
                ],
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
                                'type'     => 'string',
                                'analyzer' => 'my_analyzer',
                            ],
                            'summary'            => [
                                'type'          => 'string',
                                'index_options' => 'offsets',
                                'analyzer'      => 'my_analyzer',
                            ],
                            'description'        => [
                                'type'     => 'string',
                                'analyzer' => 'my_analyzer',
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
                                'type'   => 'string',
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
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0,
                    'analysis'           => [
                        'analyzer' => [
                            'my_analyzer' => [
                                'type'      => 'standard',
                                'stopwords' => ['a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'among', 'amongst', 'amoungst', 'amount', 'an', 'and', 'another', 'any', 'anyhow', 'anyone', 'anything', 'anyway', 'anywhere', 'are', 'around', 'as', 'at', 'back', 'be', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom', 'but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven', 'else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 'part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the'],
                            ],
                        ],
                    ],
                ],
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
