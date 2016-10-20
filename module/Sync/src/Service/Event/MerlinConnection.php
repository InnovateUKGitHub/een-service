<?php

namespace Sync\Service\Event;

use Common\Service\HttpService;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;
use Zend\Log\Logger;

class MerlinConnection
{
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

    /**
     * MerlinConnection constructor.
     *
     * @param HttpService $client
     * @param Logger      $logger
     * @param string      $username
     * @param string      $password
     * @param string      $path
     */
    public function __construct(HttpService $client, Logger $logger, $username, $password, $path)
    {
        $this->client = $client;
        $this->logger = $logger;

        $this->username = $username;
        $this->password = $password;
        $this->path = $path;
    }

    /**
     * @return \SimpleXMLElement
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

        $return['da'] = (new \DateTime())->sub(new \DateInterval('P1D'))->format('Ymd');

        return $return;
    }
}