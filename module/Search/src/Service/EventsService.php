<?php

namespace Search\Service;

use Common\Constant\EEN;
use Zend\Http\Response;

class EventsService extends AbstractSearchService
{
    /**
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
}
