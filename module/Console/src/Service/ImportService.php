<?php

namespace Console\Service;

use Zend\Http\Exception\InvalidArgumentException;
use Zend\Http\Request;

class ImportService
{
    const SERVER = 'server';
    const PORT = 'port';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PATH_GET_PROFILE = 'path-get-profile';

    /** @var HttpService */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $type;
    /** @var string */
    private $path;
    /** @var string */
    private $client;

    public function __construct(HttpService $client, $config)
    {
        if (array_key_exists(self::SERVER, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }
        if (array_key_exists(self::PORT, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the port');
        }
        if (array_key_exists(self::USERNAME, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(self::PASSWORD, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }
        if (array_key_exists(self::PATH_GET_PROFILE, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_profile');
        }

        $this->client = $client;
        $this->client->setServer($config[self::SERVER]);
        $this->client->setPort($config[self::PORT]);
        $this->client->setHeaders([
            'Content-type' => 'application/xml'
        ]);

        $this->username = $config[self::USERNAME];
        $this->password = $config[self::PASSWORD];
        $this->path = $config[self::PATH_GET_PROFILE];
    }

    public function import($type)
    {
        if ($type !== 'all') {
            $this->type = $type;
        }

        $this->client->setHttpMethod(Request::METHOD_GET);
        $this->client->setPathToService($this->path);

        $this->client->setQueryParams($this->buildQuery());
        $result = $this->client->execute(false);

        return $result;
    }

    private function buildQuery()
    {
        $return = [];
        if (empty($this->type) === false) {
            $return['pt'] = $this->type;
        }
        if (empty($this->username) === false) {
            $return['u'] = $this->username;
        }
        if (empty($this->password) === false) {
            $return['p'] = $this->password;
        }
        if (empty($this->type) === false) {
            $return['sa'] = (new \DateTime())->sub(new \DateInterval('P1M'))->format('Ymd');
        }

        return $return;
    }
}
