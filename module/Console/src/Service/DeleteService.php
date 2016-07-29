<?php

namespace Console\Service;

use Elasticsearch\Client;

class DeleteService
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
     * @param $index
     *
     * @return array
     */
    public function delete($index)
    {
        $params = [
            'index' => $index === 'all' ? '*' : $index,
        ];

        return $this->elasticSearch->indices()->delete($params);
    }
}
