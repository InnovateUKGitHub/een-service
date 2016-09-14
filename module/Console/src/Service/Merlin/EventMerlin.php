<?php

namespace Console\Service\Merlin;

use Console\Service\HttpService;
use Zend\Http\Request;
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
     * @return \SimpleXMLElement|null
     */
    public function getList()
    {
        try {
            $result = $this->client->execute(Request::METHOD_GET, $this->path, $this->buildQuery());

            return simplexml_load_string(str_replace('utf-16', 'utf-8', $result));
        } catch (HttpException $e) {
            $this->logger->debug("An error occurred during the retrieve of the merlin events");
            $this->logger->debug($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->debug("An error occurred during the retrieve of the merlin events");
            $this->logger->debug($e->getMessage());
        }

        throw new \RuntimeException("An error occurred during the retrieve of the merlin events");
    }

    /**
     * @return array
     */
    private function buildQuery()
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