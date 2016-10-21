<?php

namespace Search\Service\Query;

class ShouldQuery
{
    /** @var array */
    protected $should = [];

    /**
     * @param array  $fields
     * @param array  $values
     * @param string $operator
     */
    public function shouldFuzzy($fields, $values, $operator = 'AND')
    {
        $this->should[] = [
            'query_string' => [
                'fields' => $fields,
                'query'  => implode('~ ' . $operator . ' ', $values) . '~',
            ],
        ];
    }

    /**
     * @param string[] $fields
     * @param string   $search
     */
    public function shouldMatchPhrase($fields, $search)
    {
        $this->should[] = [
            'query_string' => [
                'fields'                 => $fields,
                'query'                  => trim($search),
                'phrase_slop'            => 50,
                'allow_leading_wildcard' => true,
                'analyze_wildcard'       => true,
                'default_operator'       => 'AND',
                'fuzzy_prefix_length'    => 3,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getShould()
    {
        return $this->should;
    }
}
