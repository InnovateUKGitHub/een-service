<?php

namespace Console\Service\Opportunity;

use Common\Service\HttpService;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;
use Zend\Log\Logger;

class OpportunityMerlin
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
     * OpportunityMerlin constructor.
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
     * @param string $month
     *
     * @return \SimpleXMLElement|null
     */
    public function getList($month)
    {
        try {
            $result = $this->client->execute(Request::METHOD_GET, $this->path, $this->buildQuery($month));

            return simplexml_load_string($result);
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
     *
     * @return array
     */
    private function buildQuery($month)
    {
        $return = [];
        if (empty($this->username) === false) {
            $return['u'] = $this->username;
        }
        if (empty($this->password) === false) {
            $return['p'] = $this->password;
        }

        $return['ub'] = (new \DateTime())->sub(new \DateInterval('P' . ($month - 1) . 'M'))->format('Ymd');
        $return['ua'] = (new \DateTime())->sub(new \DateInterval('P' . ($month) . 'M'))->format('Ymd');

        return $return;
    }
}