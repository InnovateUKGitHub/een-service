<?php

namespace Search\Service;

use Common\Constant\EEN;
use Zend\Http\Response;

class OpportunitiesService extends AbstractSearchService
{
    /**
     * @inheritdoc
     */
    public function count($params)
    {
        if ($this->query->exists(EEN::ES_INDEX_OPPORTUNITY) === false) {
            return ['total' => 0];
        }

        $this->buildSearch($params);

        return $this->query->count(EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);
    }

    /**
     * TODO When which search to use is decided, delete unnecessary code
     *
     * @param array $params
     */
    private function buildSearch($params)
    {
        if (!empty($params['search'])) {

            switch ($params['type']) {
                case 1:
                    $this->buildFullTextSearch($params['search'], ['title^3', 'summary^2', 'description^1']);
                    break;
                case 2:
                    $this->buildTermSearch($params['search'], ['title^3', 'summary^2', 'description^1']);
                    break;
                case 3:
                default:
                    $this->buildPhraseMatching($params['search'], ['title^3', 'summary^2', 'description^1']);
                    break;
            }

            $this->query->highlight([
                'title'       => [
                    'fragment_size'       => 0,
                    'number_of_fragments' => 0,
                ],
                'summary'     => [
                    'fragment_size'       => 240,
                    'number_of_fragments' => 2,
                ],
                'description' => [
                    'fragment_size'       => 240,
                    'number_of_fragments' => 2,
                ],
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
     * @inheritdoc
     */
    public function search($params)
    {
        if (!empty($params['search'])) {
            try {
                return $this->get($params['search']);
            } catch (\Exception $e) {
                // Not Found move on to search
            }
        } else {
            if ($this->query->exists(EEN::ES_INDEX_OPPORTUNITY) === false) {
                return ['total' => 0];
            }
        }

        $this->buildSearch($params);

        return $this->query->search($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        if ($this->query->exists(EEN::ES_INDEX_OPPORTUNITY) === false) {
            throw new \Exception('Opportunity not found', Response::STATUS_CODE_404);
        }

        return $this->query->getDocument($id, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);
    }
}
