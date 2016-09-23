<?php

namespace Search\Service;

use Common\Constant\EEN;

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
        if (!empty($params['search'])) {
            try {
                return $this->getOpportunity($params['search']);
            } catch (\Exception $e) {
                // Not Found move on to search
            }
        } else {
            if ($this->query->exists(EEN::ES_INDEX_OPPORTUNITY) === false) {
                return ['error' => 'Index not created'];
            }
        }

        $this->buildSearch($params);

        return $this->query->search($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getOpportunity($id)
    {
        if ($this->query->exists(EEN::ES_INDEX_OPPORTUNITY) === false) {
            return ['error' => 'Index not created'];
        }

        return $this->query->getDocument($id, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);
    }

    /**
     * @param array $params
     */
    private function buildSearch($params)
    {
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

            $this->query->highlight([
                'title' => [
                    'fragment_size' => 0,
                    'number_of_fragments' => 0,
                ],
                'summary' => [
                    'fragment_size' => 240,
                    'number_of_fragments' => 1,
                ]
            ]);
        }

        if (empty($params['opportunity_type']) === false) {
            $this->query->mustQueryString(['type'], $params['opportunity_type'], 'OR');
        }
        if (empty($params['country']) === false) {
            $this->query->mustQueryString(['country_code'], $params['country'], 'OR');
        }
    }

    /**
     * @param string $search
     */
    private function buildFullTextSearch($search)
    {
        $searches = explode(' ', trim($search));
        $this->query->mustQueryString(['title^5', 'summary^2', 'description'], $searches);
    }

    /**
     * @param string $search
     */
    private function buildPhraseMatching($search)
    {
        $this->query->shouldMatchPhrase('title', $search);
        $this->query->shouldMatchPhrase('summary', $search);
        $this->query->shouldMatchPhrase('description', $search);
    }

    /**
     * @param string $search
     */
    private function buildTermSearch($search)
    {
        $searches = explode(' ', trim($search));

        $this->query->mustFuzzy(['title^5', 'summary^2', 'description'], $searches);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function searchEvents($params)
    {
        if ($this->query->exists(EEN::ES_INDEX_EVENT) === false) {
            return ['error' => 'Index not created'];
        }

        $searches = explode(' ', trim($params['search']));
        $this->query->mustQueryString(['title', 'description'], $searches);
        $this->query->mustRange('end_date', 'now/d', 'gte');
        $this->query->mustExist(['url']);

        return $this->query->search($params, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getEvent($id)
    {
        if ($this->query->exists(EEN::ES_INDEX_EVENT) === false) {
            return ['error' => 'Index not created'];
        }

        return $this->query->getDocument($id, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->query->getCountryList();
    }
}
