<?php
namespace Console\Service;

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
    public function createIndex($index)
    {
        if ($this->exists($index)) {
            return;
        }
        $this->create($index);
    }

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
            throw new \RuntimeException('An error occurred during the creation of the index');
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
        }
        throw new \RuntimeException('An error occurred during the import of a document');
    }

    /**
     * @param array $results
     * @param int   $from
     * @param int   $size
     *
     * @return array|null
     */
    public function getAll($results = [], $from = 0, $size = 100)
    {
        $query = [
            'index'   => ES_INDEX_OPPORTUNITY,
            'type'    => ES_TYPE_OPPORTUNITY,
            'from'    => $from,
            'size'    => $size,
            '_source' => ['id', 'date', 'deadline', 'date_import'],
        ];

        try {
            $tmp = $this->elasticSearch->search($query);
            if (empty($results)) {
                $results = $tmp;
            } else {
                $results['hits']['hits'] = array_merge($results['hits']['hits'], $tmp['hits']['hits']);
            }

            if (count($tmp['hits']['hits']) > 0) {
                return $this->getAll($results, $from + $size);
            }

            return $results;
        } catch (\Exception $e) {
            $this->logger->debug('An error occurred during the removal of old documents');
            $this->logger->debug($e->getMessage());
        }
        throw new \RuntimeException('An error occurred during the removal of old documents');
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function delete($params)
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