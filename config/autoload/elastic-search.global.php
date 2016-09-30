<?php

use Common\Constant\EEN;

return [
    'elastic-search-indexes' => [
        EEN::ES_INDEX_COUNTRY     => [
            'index' => EEN::ES_INDEX_COUNTRY,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    EEN::ES_TYPE_COUNTRY => [
                        'properties' => [
                            'name'        => [
                                'type' => 'string',
                            ],
                            'date_import' => [
                                'type' => 'date',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        EEN::ES_INDEX_OPPORTUNITY . EEN::ES_INDEX_WORDS => [
            'index' => EEN::ES_INDEX_OPPORTUNITY . EEN::ES_INDEX_WORDS,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    EEN::ES_TYPE_OPPORTUNITY . EEN::ES_INDEX_WORDS => [
                        'properties' => [
                            'word' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        EEN::ES_INDEX_OPPORTUNITY                       => [
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
                            'type'              => [
                                'type' => 'string',
                            ],
                            'title'             => [
                                'type'     => 'string',
                                'analyzer' => 'my_analyzer',
                            ],
                            'summary'           => [
                                'type'     => 'string',
                                'analyzer' => 'my_analyzer',
                            ],
                            'description'       => [
                                'type'     => 'string',
                                'analyzer' => 'my_analyzer',
                            ],
                            'partner_expertise' => [
                                'type' => 'string',
                            ],
                            'advantage'         => [
                                'type' => 'string',
                            ],
                            'stage'             => [
                                'type' => 'string',
                            ],
                            'ipr'               => [
                                'type' => 'string',
                            ],
                            'ipr_comment'       => [
                                'type' => 'string',
                            ],
                            'country_code'      => [
                                'type' => 'string',
                            ],
                            'country'           => [
                                'type' => 'string',
                            ],
                            'date_create'       => [
                                'type' => 'date',
                            ],
                            'date'              => [
                                'type' => 'date',
                            ],
                            'deadline'          => [
                                'type' => 'date',
                            ],
                            'date_import'       => [
                                'type' => 'date',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        EEN::ES_INDEX_EVENT . EEN::ES_INDEX_WORDS       => [
            'index' => EEN::ES_INDEX_EVENT . EEN::ES_INDEX_WORDS,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    EEN::ES_INDEX_EVENT . EEN::ES_INDEX_WORDS => [
                        'properties' => [
                            'word' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        EEN::ES_INDEX_EVENT                             => [
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
                            'title'        => [
                                'type' => 'string',
                            ],
                            'summary'      => [
                                'type' => 'string',
                            ],
                            'description'  => [
                                'type' => 'string',
                            ],
                            'start_date'   => [
                                'type' => 'date',
                            ],
                            'end_date'     => [
                                'type' => 'date',
                            ],
                            'url'          => [
                                'type' => 'string',
                            ],
                            'country_code' => [
                                'type' => 'string',
                            ],
                            'country'      => [
                                'type' => 'string',
                            ],
                            'city'         => [
                                'type' => 'string',
                            ],
                            'fee'          => [
                                'type' => 'integer',
                            ],
                            'type'         => [
                                'type' => 'string',
                            ],
                            'date_import'  => [
                                'type' => 'date',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
