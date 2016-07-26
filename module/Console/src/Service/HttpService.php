<?php
namespace Console\Service;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Json\Server\Exception\HttpException;

class HttpService
{
    /** @var string */
    private $server;
    /** @var integer */
    private $port;
    /** @var string */
    private $userName;
    /** @var string */
    private $password;
    /** @var string */
    private $pathToService;
    /** @var string */
    private $version;
    /** @var string http or https */
    private $httpScheme = 'http';
    /** @var string Request::METHOD_GET|PUT|POST|DELETE etc. */
    private $httpMethod = Request::METHOD_GET;
    /** @var Client */
    private $client;

    /**
     * HttpService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $httpMethod
     *
     * @return $this
     */
    public function setHttpMethod($httpMethod)
    {
        switch ($httpMethod) {
            case 'GET':
                $method = Request::METHOD_GET;
                break;
            case 'PUT':
                $method = Request::METHOD_PUT;
                break;
            case 'POST':
                $method = Request::METHOD_POST;
                break;
            case 'DELETE':
                $method = Request::METHOD_DELETE;
                break;
            default:
                throw new InvalidArgumentException('Unsupported HTTP method ' . $httpMethod);
        }
        $this->client->setMethod($method);

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * The part of the URL after the hostname
     *
     * @param string $pathToService
     *
     * @return self
     */
    public function setPathToService($pathToService)
    {
        $this->pathToService = $pathToService;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathToService()
    {
        return $this->pathToService;
    }

    /**
     * @param string $requestBody
     *
     * @return $this
     */
    public function setRequestBody($requestBody)
    {
        $this->client->setRawBody($requestBody);

        return $this;
    }

    /**
     * @param string $userName
     *
     * @return $this
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param string $server
     *
     * @return $this
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @param string $port
     *
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->client->setHeaders($headers);

        return $this;
    }

    public function setQueryParams($params)
    {
        $this->client->setParameterGet($params);
    }

    /**
     * @param bool $json
     *
     * @return string json
     */
    public function execute($json = true)
    {
        $uri = $this->buildUri();
        $this->client->setUri($uri);

        $response = $this->client->send();

        if ($json === false) {
            return $response->getContent();
        }

        $rawContent = $response->getBody();
        $content = json_decode($rawContent, true);
        if ($content === null) {
            throw new HttpException('Malformed JSON response: ' . (string)$rawContent);
        }

        return $content;
    }

    /**
     * @return string
     */
    private function buildUri()
    {
        $uri = $this->httpScheme . '://';
        if (!empty($this->userName) || !empty($this->password)) {
            $uri .= $this->userName . '.' . $this->password . '@';
        }
        $uri .= $this->server;
        if (!empty($this->port)) {
            $uri .= ':' . $this->port;
        }
        if (!empty($this->version)) {
            $uri .= '/' . $this->version;
        }
        if (!empty($this->pathToService)) {
            $uri .= '/' . $this->pathToService;
        }

        return $uri;
    }
}
