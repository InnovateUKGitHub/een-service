<?php

namespace Search\Service;

class ElasticSearchService
{
    /** @var QueryService */
    private $query;

    /**
     * ElasticSearchService constructor.
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
    public function searchOpportunities($params)
    {
        if ($this->query->exists(ES_INDEX_OPPORTUNITY) === false) {
            return ['error' => 'Index not created'];
        }

        return $this->query->search($params, ES_INDEX_OPPORTUNITY, ES_TYPE_OPPORTUNITY);
    }

    public function searchOpportunity($id)
    {
        if ($this->query->exists(ES_INDEX_OPPORTUNITY) === false) {
            return ['error' => 'Index not created'];
        }

        return $this->query->getDocument($id, ES_INDEX_OPPORTUNITY, ES_TYPE_OPPORTUNITY);
    }
}
