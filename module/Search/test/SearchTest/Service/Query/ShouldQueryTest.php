<?php

namespace SearchTest\Service\Query;

use Search\Service\Query\ShouldQuery;

/**
 * @covers \Search\Service\Query\ShouldQuery
 */
class ShouldQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldFuzzy()
    {
        $service = new ShouldQuery();

        $service->shouldFuzzy(['fields'], ['values']);
        self::assertEquals([[
            'query_string' => [
                'fields' => ['fields'],
                'query'  => 'values~',
            ],
        ]], $service->getShould());
    }

    public function testShouldMatchPhrase()
    {
        $service = new ShouldQuery();

        $service->shouldMatchPhrase(['fields'], 'value');
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
        ]], $service->getShould());
    }
}
