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
        $this->must[] = [
            'query_string' => [
                'fields'      => $fields,
                'query'       => $value,
                'phrase_slop' => 50,
            ],
        ];
    }
}
