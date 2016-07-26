<?php

return [
    'import [--type=<type>]' => 'import date from merlin into elasticSearch',
    ['--type', 'Type of data to be imported. [bo|all] (default: all)'],
    'generate [--index=<index>] [--number=<number>]' => 'Generate random data into elasticSearch for test purpose',
    ['--index', 'Index to generate. [opportunity|event|all] (default: all)'],
    ['--number', 'Number of documents to generate. (default: 10)'],
    'delete [--index=<index>]' => 'Delete elasticSearch index type',
    ['--index', 'Index to delete. [opportunity|event|all] (default: all)'],
];
