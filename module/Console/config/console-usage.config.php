<?php

return [
    'Usage information',
    'import [--index=<index>] [--month=<month>] [--type=<type>]' => 'import data into elasticSearch',
    ['--index', 'The index to import (default: opportunity)'],
    ['--month', 'The number amount of month to go back. [1|2|3|4|5|6|7|8|9|10|11|12] (default: 1)'],
    'delete [--index=<index>]'                                   => 'delete old data of elasticSearch',
    ['--index', 'The index to delete (default: opportunity)'],
    'generate [--index=<index>] [--number=<number>]'             => 'Generate random data into elasticSearch for test purpose',
    ['--index', 'Index to generate. [opportunity|event|all] (default: all)'],
    ['--number', 'Number of documents to generate. (default: 10)'],
    'purge [--index=<index>]'                                    => 'Delete elasticSearch index type',
    ['--index', 'Index to delete. [opportunity|event|all] (default: all)'],
];
