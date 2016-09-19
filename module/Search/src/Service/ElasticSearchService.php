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

        if (empty($params['search'])) {
            $this->buildFullTextSearch($params['search']);
        } else {
            switch ($params['type']) {
                case 3:
                    $this->buildPhraseMatching($params['search']);
                    break;
                case 2:
                    $this->buildTermSearch($params['search']);
                    break;
                case 1:
                default:
                    $this->buildFullTextSearch($params['search']);
                    break;
            }
        }

        if (empty($params['opportunity_type']) === false) {
            $this->query->mustQueryString(['type'], $params['opportunity_type'], 'OR');
        }

        return $this->query->search($params, ES_INDEX_OPPORTUNITY, ES_TYPE_OPPORTUNITY);
    }

    private function buildPhraseMatching($search)
    {
//        $searches = explode(' ', trim($search));

//        $this->query->mustMatchPhrase(['title', 'summary', 'description'], $searches);
        $this->query->shouldMatchPhrase('title', $search);
        $this->query->shouldMatchPhrase('summary', $search);
        $this->query->shouldMatchPhrase('description', $search);
    }

    private function buildTermSearch($search)
    {
        $searches = explode(' ', trim($search));

        $this->query->mustFuzzy(['title^5', 'summary^2', 'description'], $searches);
    }

    private function buildFullTextSearch($search)
    {
        $searches = explode(' ', trim($search));
        $this->query->mustQueryString(['title^5', 'summary^2', 'description'], $searches);
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
        $this->query->mustQueryString(['title', 'description'], $searches);
        $this->query->mustRange('end_date', 'now/d', 'gte');
        $this->query->mustExist(['url']);

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
