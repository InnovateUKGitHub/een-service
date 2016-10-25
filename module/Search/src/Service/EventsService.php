<?php

namespace Search\Service;

use Common\Constant\EEN;

class EventsService extends AbstractSearchService
{
    /**
     * @inheritdoc
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

        return $this->query->search($params, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        if ($this->query->exists(EEN::ES_INDEX_EVENT) === false) {
            return ['total' => 0];
        }

        return $this->query->getDocument($id, EEN::ES_INDEX_EVENT, EEN::ES_TYPE_EVENT);
    }
}
