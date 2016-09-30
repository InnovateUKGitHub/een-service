<?php

namespace Search\Service;

abstract class AbstractSearchService
{
    /** @var QueryService */
    protected $query;

    /**
     * OpportunitiesService constructor.
     *
     * @param QueryService $query
     */
    public function __construct(QueryService $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    abstract public function count($params);

    /**
     * @param array $params
     *
     * @return array
     */
    abstract public function search($params);

    /**
     * @param string $id
     *
     * @return array
     */
    abstract public function get($id);

    /**
     * @param string $search
     * @param array  $fields
     */
    protected function buildFullTextSearch($search, $fields)
    {
        $searches = explode(' ', trim($search));
        $this->query->mustQueryString($fields, $searches);
    }

    /**
     * @codeCoverageIgnore
     * @param string $search
     * @param array  $fields
     */
    protected function buildPhraseMatching($search, $fields)
    {
        $this->query->mustMatchPhrase($fields, $search);
    }

    /**
     * @codeCoverageIgnore
     * @param string $search
     * @param array  $fields
     */
    protected function buildTermSearch($search, $fields)
    {
        $searches = explode(' ', trim($search));

        $this->query->mustFuzzy($fields, $searches);
    }
}
