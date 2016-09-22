<?php

namespace Contact\Service;

use Zend\Soap\Client;

class SalesForceService
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const TOKEN = 'token';
    const SF_NAMESPACE = 'namespace';

    /** @var Client */
    private $client;

    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $namespace;

    /**
     * MailService constructor.
     *
     * @param Client $client
     * @param array  $config
     */
    public function __construct(Client $client, $config)
    {
        $this->client = $client;
        $this->username = $config[self::USERNAME];
        $this->password = $config[self::PASSWORD] . $config[self::TOKEN];
        $this->namespace = $config[self::SF_NAMESPACE];
    }

    public function login()
    {
        $loginResult = $this->client->call(
            'login',
            [
                'login' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]
        );

        // Set new Url retrieve from the login
        $this->client->setUri($loginResult->result->serverUrl);
        $this->client->setLocation($loginResult->result->serverUrl);

        // attach the session id to the soap client
        $header = new \SoapHeader(
            $this->namespace,
            'SessionHeader',
            ['sessionId' => $loginResult->result->sessionId]
        );
        $this->client->addSoapInputHeader($header, true);
    }

    private function logout()
    {
        $this->client->call('logout', ['logout' => []]);
    }

    /**
     * @return array
     */
    public function getUserInfo()
    {
        $this->login();
        $info = $this->client->call('getUserInfo', ['getUserInfo' => []]);
        $this->logout();

        return json_decode(json_encode($info), true);
    }
}
