<?php

return [
    'import [--month=<month>]'                       => 'import date from merlin into elasticSearch',
    ['--month', 'The number amount of month to go back. [1|2|3|4|5|6|7|8|9|10|11|12] (default: 1)'],
    'delete [--since=<since>]'                       => 'import date from merlin into elasticSearch',
    ['--since', 'The number amount of month out of date (default: 12)'],
    'generate [--index=<index>] [--number=<number>]' => 'Generate random data into elasticSearch for test purpose',
    ['--index', 'Index to generate. [opportunity|event|all] (default: all)'],
    ['--number', 'Number of documents to generate. (default: 10)'],
    'delete-all [--index=<index>]'                   => 'Delete elasticSearch index type',
    ['--index', 'Index to delete. [opportunity|event|all] (default: all)'],
];
