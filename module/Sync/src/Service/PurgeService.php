<?php

namespace Sync\Service;

use Elasticsearch\Client;

class PurgeService
{
    /** @var Client */
    private $elasticSearch;

    /**
     * DeleteService constructor.
     *
     * @param Client $elasticSearch
     */
    public function __construct(Client $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    /**
     * Function used only to clean all the indexes
     *
     * @param $index
     *
     * @return array
     */
    public function purge($index)
    {
        $params = [
            'index' => $index === 'all' ? '*' : $index,
        ];

        return $this->elasticSearch->indices()->delete($params);
    }
}
