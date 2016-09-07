<?php

namespace Console\Service\Merlin;

use Console\Service\HttpService;
use Zend\Json\Server\Exception\HttpException;
use Zend\Log\Logger;

class EventMerlin
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PATH_GET_EVENT = 'path-get-event';

    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $path;

    /** @var HttpService */
    private $client;
    /** @var Logger */
    private $logger;

    public function __construct(HttpService $client, Logger $logger, $config)
    {
        $this->client = $client;
        $this->username = $config[self::USERNAME];
        $this->password = $config[self::PASSWORD];
        $this->path = $config[self::PATH_GET_EVENT];
        $this->logger = $logger;
    }

    /**
     * @param string $month
     * @param string $type
     *
     * @return \SimpleXMLElement|null
     */
    public function getList($month, $type)
    {
        $this->client->setPathToService($this->path);
        $this->client->setQueryParams($this->buildQuery($month, $type));

        try {
            return simplexml_load_string($this->client->execute());
        } catch (HttpException $e) {
            $this->logger->debug("An error occurred during the retrieve of the $month month");
            $this->logger->debug($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->debug("An error occurred during the retrieve of the $month month");
            $this->logger->debug($e->getMessage());
        }

        throw new \RuntimeException("An error occurred during the retrieve of the $month month");
    }

    /**
     * @param string $month
     * @param string $type
     *
     * @return array
     */
    private function buildQuery($month, $type)
    {
        $return = [];
        if (empty($this->username) === false) {
            $return['u'] = $this->username;
        }
        if (empty($this->password) === false) {
            $return['p'] = $this->password;
        }

        return $return;
    }
}