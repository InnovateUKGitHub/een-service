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
                'fields'      => $fields,
                'query'       => $search,
                'phrase_slop' => 50,
            ],
        ];
    }
}
