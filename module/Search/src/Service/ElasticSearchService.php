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

        $searches = explode(' ', trim($params['search']));
        $this->query->buildQuery(['title', 'summary', 'description'], $searches);
        if (empty($params['opportunity_type']) === false) {
            $this->query->buildQuery(['type'], $params['opportunity_type'], 'OR');
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

    /**
     * @param array $params
     *
     * @return array
     */
    public function searchEvents($params)
    {
        if ($this->query->exists(ES_INDEX_EVENT) === false) {
            return ['error' => 'Index not created'];
        }

        $searches = explode(' ', trim($params['search']));
        $this->query->buildQuery(['title', 'description'], $searches);
        $this->query->buildRangeQuery('end_date', 'now/d', 'gte');
        $this->query->buildNotNullQuery(['url']);

        return $this->query->search($params, ES_INDEX_EVENT, ES_TYPE_EVENT);
    }

    public function searchEvent($id)
    {
        if ($this->query->exists(ES_INDEX_EVENT) === false) {
            return ['error' => 'Index not created'];
        }

        return $this->query->getDocument($id, ES_INDEX_EVENT, ES_TYPE_EVENT);
    }
}
