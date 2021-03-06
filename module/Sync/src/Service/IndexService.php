<?php
namespace Sync\Service;

use Elasticsearch\Client;
use Zend\Log\Logger;

class IndexService
{
    /** @var Client */
    private $elasticSearch;
    /** @var Logger */
    private $logger;
    /** @var array */
    private $config;

    /**
     * @param Client $elasticSearch
     * @param Logger $logger
     * @param array  $config
     */
    public function __construct(Client $elasticSearch, Logger $logger, $config)
    {
        $this->elasticSearch = $elasticSearch;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param string $index
     */
    public function createSettings($index)
    {
        $params = [
            'index' => $index,
            'body'  => $this->config[$index]['body']['settings'],
        ];

        $params['body']['number_of_shards'] = null;
        $params['body']['number_of_replicas'] = null;
        $this->elasticSearch->indices()->close(['index' => $index]);
        $this->elasticSearch->indices()->putSettings($params);
        $this->elasticSearch->indices()->open(['index' => $index]);
    }

    /**
     * @param string $index
     */
    public function createIndex($index)
    {
        if ($this->exists($index)) {
            return;
        }
        $this->create($index);
//        $this->create($index . EEN::ES_INDEX_WORDS);
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    private function exists($index)
    {
        try {
            return $this->elasticSearch->indices()->exists(['index' => $index]);
        } catch (\Exception $e) {
            $this->logger->debug('An error occurred during the creation of the index');
            $this->logger->debug($e->getMessage());
        }
        throw new \RuntimeException('An error occurred during the creation of the index');
    }

    /**
     * Function to create the mapping of the elastic index
     *
     * @param string $index
     */
    private function create($index)
    {
        $params = $this->config[$index];

        try {
            $this->elasticSearch->indices()->create($params);
        } catch (\Exception $e) {
            $this->logger->debug('An error occurred during the creation of the index');
            $this->logger->debug($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param array  $values
     * @param string $id
     * @param string $index
     * @param string $type
     *
     * @return array
     */
    public function index($values, $id, $index, $type)
    {
        $params = [
            'body'  => $values,
            'index' => $index,
            'type'  => $type,
            'id'    => $id,
        ];

        try {
            return $this->elasticSearch->index($params);
        } catch (\Exception $e) {
            $this->logger->debug('An error occurred during the import of a document');
            $this->logger->debug($e->getMessage());
            $this->logger->debug($params);
            $this->logger->debug($e->getTraceAsString());
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param string $index
     * @param string $type
     * @param string $now
     * @param array  $results
     * @param int    $from
     * @param int    $size
     *
     * @return array|null
     */
    public function getOutOfDateData($index, $type, $now, $results = [], $from = 0, $size = 100)
    {
        $query = [
            'index' => $index,
            'type'  => $type,
            'from'  => $from,
            'size'  => $size,
            'body'  => [
                'query' => [
                    'range' => [
                        'date_import' => [
                            'lt' => $now,
                        ],
                    ],
                ],
            ],
        ];

        try {
            $tmp = $this->elasticSearch->search($query);
            if (empty($results)) {
                $results = $tmp;
            } else {
                $results['hits']['hits'] = array_merge($results['hits']['hits'], $tmp['hits']['hits']);
            }

            if (count($tmp['hits']['hits']) > 0) {
                return $this->getOutOfDateData($index, $type, $now, $results, $from + $size);
            }

            return $results;
        } catch (\Exception $e) {
            $this->logger->debug('An error occurred during the removal of old documents');
            $this->logger->debug($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function bulk($params)
    {
        try {
            $this->elasticSearch->bulk($params);

            return true;
        } catch (\Exception $e) {
            $this->logger->debug('An error occurred during the removal of old documents');
            $this->logger->debug($e->getMessage());
        }
        throw new \RuntimeException('An error occurred during the removal of old documents');
    }
}