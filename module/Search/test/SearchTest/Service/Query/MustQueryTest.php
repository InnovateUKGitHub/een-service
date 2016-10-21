<?php

namespace SearchTest\Service\Query;

use Search\Service\Query\MustQuery;

/**
 * @covers \Search\Service\Query\MustQuery
 */
class MustQueryTest extends \PHPUnit_Framework_TestCase
{
    const INDEX = 'index';
    const TYPE = 'type';
    const OPPORTUNITY_ID = 'myId';

    public function testMustQueryString()
    {
        $service = new MustQuery();

        $service->mustQueryString(['fields'], ['values']);
        self::assertEquals([[
            'query_string' => [
                'fields' => ['fields'],
                'query'  => 'values*',
            ],
        ]], $service->getMust());
    }

    public function testMustRange()
    {
        $service = new MustQuery();

        $service->mustRange('field', 'value', 'lt');
        self::assertEquals([[
            'range' => [
                'field' => [
                    'lt' => 'value',
                ],
            ],
        ]], $service->getMust());
    }

    public function testMustExist()
    {
        $service = new MustQuery();

        $service->mustExist('field');
        self::assertEquals([[
            'exists' => [
                'field' => 'field',
            ],
        ]], $service->getMust());
    }

    public function testMustFuzzy()
    {
        $service = new MustQuery();

        $service->mustFuzzy(['fields'], ['values']);
        self::assertEquals([[
            'query_string' => [
                'fields' => ['fields'],
                'query'  => 'values~',
            ],
        ]], $service->getMust());
    }

    public function testMustMatchPhrase()
    {
        $service = new MustQuery();

        $service->mustMatchPhrase(['fields'], 'value');
        self::assertEquals([[
            'query_string' => [
                'fields'                 => ['fields'],
                'query'                  => 'value',
                'phrase_slop'            => 50,
                'allow_leading_wildcard' => true,
                'analyze_wildcard'       => true,
                'default_operator'       => 'AND',
                'fuzzy_prefix_length'    => 3,
            ],
        ]], $service->getMust());
    }
}
