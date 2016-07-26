<?php

namespace Console\Service;

use Zend\Http\Exception\InvalidArgumentException;

class ConnectionService
{
    const SERVER = 'server';
    const PORT = 'port';
    const CONTENT_TYPE = 'content-type';
    const ACCEPT = 'accept';

    /** @var HttpService */
    private $client;

    public function __construct(HttpService $client, $config)
    {
        if (array_key_exists(self::SERVER, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }
        if (array_key_exists(self::PORT, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the port');
        }
        if (array_key_exists(self::ACCEPT, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the accept type');
        }
        if (array_key_exists(self::CONTENT_TYPE, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the content type');
        }

        $this->client = $client;

        $this->client->setServer($config[self::SERVER]);
        $this->client->setPort($config[self::PORT]);
        $this->client->setHeaders([
            'Accept'       => $config[self::ACCEPT],
            'Content-type' => $config[self::CONTENT_TYPE],
        ]);
    }

    /**
     * Executes HTTP Service request
     *
     * @param string $method
     * @param string $path
     * @param array  $body
     *
     * @return array
     */
    public function execute($method, $path, $body = null)
    {
        $this->client->setHttpMethod($method);
        $this->client->setPathToService($path);
        $this->client->setRequestBody(null);
        if ($body !== null) {
            $this->client->setRequestBody(json_encode($body));
        }
        $result = $this->client->execute();

        return $result;
    }
}