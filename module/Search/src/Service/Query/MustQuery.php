<?php

namespace Search\Service\Query;

class MustQuery extends ShouldQuery
{
    /** @var array */
    protected $must = [];

    /**
     * @param array  $fields
     * @param array  $values
     * @param string $operator
     */
    public function mustQueryString($fields, $values, $operator = 'AND')
    {
        $this->must[] = [
            'query_string' => [
                'fields' => $fields,
                'query'  => implode('* ' . $operator . ' ', $values) . '*',
            ],
        ];
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $operator
     */
    public function mustRange($field, $value, $operator)
    {
        $this->must[] = [
            'range' => [
                $field => [
                    $operator => $value,
                ],
            ],
        ];
    }

    /**
     * @param string $field
     */
    public function mustExist($field)
    {
        $this->must[] = [
            'exists' => [
                'field' => $field,
            ],
        ];
    }

    /**
     * @param array  $fields
     * @param array  $values
     * @param string $operator
     */
    public function mustFuzzy($fields, $values, $operator = 'AND')
    {
        $this->must[] = [
            'query_string' => [
                'fields' => $fields,
                'query'  => implode('~ ' . $operator . ' ', $values) . '~',
            ],
        ];
    }

    /**
     * @param array $fields
     * @param array $value
     */
    public function mustMatchPhrase($fields, $value)
    {
        $value = preg_replace('/distrib(\w*)/', '', $value);
        $value = preg_replace('/manufact(\w*)/', '', $value);
        $this->must[] = [
            'query_string' => [
                'fields'                 => $fields,
                'query'                  => trim($value),
                'phrase_slop'            => 5,
                'allow_leading_wildcard' => true,
                'analyze_wildcard'       => true,
                'default_operator'       => 'AND',
                'fuzzy_prefix_length'    => 3,
            ],
        ];
    }
}
