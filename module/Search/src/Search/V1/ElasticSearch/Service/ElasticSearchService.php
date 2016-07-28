<?php

namespace Search\V1\ElasticSearch\Service;

class ElasticSearchService
{
    const OPPORTUNITY = 'opportunity';
    const EVENT = 'event';

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

    public function searchOpportunities($params)
    {
        if ($this->query->exists(self::OPPORTUNITY) === false) {
            return ['error' => 'Index not created'];
        }
        return $this->query->search($params, self::OPPORTUNITY, self::OPPORTUNITY);
    }

    public function searchEvent($params)
    {
        if ($this->query->exists(self::EVENT) === false) {
            return ['error' => 'Index not created'];
        }
        return $this->query->search($params, self::EVENT, self::EVENT);
    }
}
