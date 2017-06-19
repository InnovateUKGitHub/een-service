<?php

namespace Search\Service;

use Common\Constant\EEN;
use Zend\Http\Response;

class EventsService extends AbstractSearchService
{
    /**
     * This function is not use at the moment
     *
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function count($params)
    {
    }

    /**
     * @inheritdoc
     */
    public function search($params)
    {
        if ($this->query->exists(EEN::ES_INDEX_EVENT) === false) {
            return ['total' => 0];
        }

        $searches = explode(' ', trim($params['search']));
        $this->query->mustQueryString(['title', 'description'], $searches);

        $dateType = is_array($params['date_type']) ? array_pop($params['date_type']) : null;
        $country = is_array($params['country']) ? array_pop($params['country']) : null;

        if ($dateType === null || $dateType === 'any') {
            $this->query->mustRange('end_date', 'now/d', 'gte');
        } else if ($params['date_from'] && $params['date_to']) {
            $this->query->mustRange('start_date', $params['date_from'], 'gte', 'dd/MM/yyyy');
            $this->query->mustRange('end_date', $params['date_to'], 'lte', 'dd/MM/yyyy');
        }
        if ($country != null && $country !== 'anywhere') {
            if ($country === 'europe') {
                $countries = $this->getEuropeCountries();
            } else {
                $countries = $this->getUkCountries();
            }
            $this->query->mustQueryString(['country_code'], $countries, 'OR');
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

        return $this->query->search($params, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        if ($this->query->exists(EEN::ES_INDEX_EVENT) === false) {
            throw new \Exception('Event not found', Response::STATUS_CODE_404);
        }

        return $this->query->getDocument($id, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);
    }

    private function getEuropeCountries()
    {
        return [
            'ad', 'al', 'at', 'ba', 'be', 'bg', 'by', 'ch', 'cy', 'cz',
            'de', 'dk', 'ee', 'es', 'fi', 'fo', 'fr', 'gb', 'gi', 'gr',
            'hr', 'hu', 'ie', 'im', 'is', 'it', 'li', 'lt', 'lu', 'lv',
            'mc', 'md', 'me', 'mk', 'mt', 'nl', 'no', 'pl', 'pt', 'ro',
            'rs', 'ru', 'se', 'si', 'sk', 'sm', 'ua', 'uk', 'va', 'uk',
        ];
    }

    private function getUkCountries()
    {
        return [
            'gb', 'uk', 'ie', 'im'
        ];
    }
}
