<?php

namespace Contact\Service;

use Zend\Http\Response;
use Zend\Soap\Client;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

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

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
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
     * @param \SoapParam $object
     *
     * @return array|ApiProblemResponse
     */
    public function create(\SoapParam $object)
    {
        $this->login();

        $response = $this->client->call(
            'create',
            ['create' => $object]
        );

        $this->logout();

        if ($response->result->success === false) {
            return $this->buildValidationErrors($response->result->errors);
        }
        $id = $response->result->id;

        return ['id' => $id];
    }

    public function buildValidationErrors($errors)
    {
        $validationMessages = [];

        if (is_array($errors->fields)) {
            foreach ($errors->fields as $field) {
                $validationMessages[strtolower($field)] = [
                    'isEmpty' => 'Value is required and can\'t be empty',
                ];
            }
        } else {
            $validationMessages[strtolower($errors->fields)] = [
                'isEmpty' => 'Value is required and can\'t be empty',
            ];
        }

        return new ApiProblemResponse(
            new ApiProblem(
                Response::STATUS_CODE_422,
                'Failed Validation',
                null,
                null,
                [
                    'validation_messages' => $validationMessages,
                ]
            )
        );
    }
}
