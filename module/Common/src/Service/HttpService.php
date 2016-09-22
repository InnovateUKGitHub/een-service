<?php
namespace Common\Service;

use Zend\Http\Client;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Http\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;

class HttpService
{
    /** @var string */
    private $server;
    /** @var string */
    private $userName;
    /** @var string */
    private $password;
    /** @var string */
    private $pathToService;
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
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
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
    public function getPathToService()
    {
        return $this->pathToService;
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
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->httpScheme = $scheme;

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
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->client->setHeaders($headers);

        return $this;
    }

    /**
     * @param $params
     *
     * @return $this
     */
    public function setQueryParams($params)
    {
        $this->client->setParameterGet($params);

        return $this;
    }

    /**
     * @param string $request
     * @param string $path
     * @param array  $params
     * @param array  $body
     * @param bool   $json
     *
     * @return string json
     */
    public function execute($request, $path, $params = [], $body = [], $json = false)
    {
        $this->setHttpMethod($request);
        $this->setPathToService($path);

        if ($request === Request::METHOD_GET && empty($params) === false) {
            $this->client->setParameterGet($params);
        }
        if ($request === Request::METHOD_POST && empty($body) === false) {
            $this->client->setRawBody(json_encode($body));
        }

        $this->client->setUri($this->buildUri());

        try {
            $response = $this->client->send();
        } catch (RuntimeException $e) {
            throw new HttpException($e->getMessage());
        }

        if ($json) {
            return json_decode($response->getBody(), true);
        }

        return $response->getContent();
    }

    /**
     * @return string
     */
    private function buildUri()
    {
        if (!empty($this->userName) || !empty($this->password)) {
            $this->client->setAuth($this->userName, $this->password);
        }

        $uri = $this->httpScheme . '://' . $this->server;

        if (!empty($this->pathToService)) {
            $uri .= $this->pathToService;
        }

        return $uri;
    }
}
