<?php

return [
    'Usage information',
    'import [--index=<index>] [--month=<month>] [--type=<type>]' => 'import date from merlin into elasticSearch',
    ['--index', 'The index to delete (default: opportunity)'],
    ['--month', 'The number amount of month to go back. [1|2|3|4|5|6|7|8|9|10|11|12] (default: 1)'],
    ['--type', 'The type of data to import from. [s|u] (default: u)'],
    'delete [--index=<index>]'                                   => 'import date from merlin into elasticSearch',
    ['--index', 'The index to delete (default: opportunity)'],
    'generate [--index=<index>] [--number=<number>]'             => 'Generate random data into elasticSearch for test purpose',
    ['--index', 'Index to generate. [opportunity|event|all] (default: all)'],
    ['--number', 'Number of documents to generate. (default: 10)'],
    'delete-all [--index=<index>]'                               => 'Delete elasticSearch index type',
    ['--index', 'Index to delete. [opportunity|event|all] (default: all)'],
];
