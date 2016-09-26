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

    /** @var string */
    private $sessionId;

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

        return json_decode(json_encode($info), true);
    }

    public function login()
    {
        if ($this->sessionId) {
            return;
        }

        $loginResult = $this->client->call(
            'login',
            [
                'login' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]
        );
        // TODO Log failing error to logger

        // Set new Url retrieve from the login
        $this->client->setUri($loginResult->result->serverUrl);
        $this->client->setLocation($loginResult->result->serverUrl);

        // attach the session id to the soap client
        $this->sessionId = $loginResult->result->sessionId;
        $header = new \SoapHeader(
            $this->namespace,
            'SessionHeader',
            ['sessionId' => $this->sessionId]
        );
        $this->client->addSoapInputHeader($header, true);
    }

    public function logout()
    {
        $this->client->call('logout', ['logout' => []]);
        // TODO Log failing error to logger
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function describesObject($type)
    {
        $this->login();

        $data = new \stdClass();
        $data->sObjectType = $type;
        $object = new \SoapVar($data, SOAP_ENC_OBJECT, $type, $this->namespace);
        $object = new \SoapParam($object, 'sObjectType');

        $response = $this->client->call(
            'describeSObject',
            ['describeSObject' => $object]
        );

        return $response;
    }

    /**
     * @param array $ids
     */
    public function delete($ids)
    {
        $this->login();

        $this->client->call(
            'delete',
            ['delete' => $ids]
        );
        // TODO Log failing error to logger
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
        // TODO Log failing error to logger
        if ($response->result->success == false && isset($response->result->errors)) {
            return $this->buildValidationErrors($response->result->errors);
        }

        return ['id' => $response->result->id];
    }

    /**
     * @param \stdClass $errors
     *
     * @return ApiProblemResponse
     */
    public function buildValidationErrors($errors)
    {
        $validationMessages = [];
        if (is_array($errors->fields)) {
            foreach ($errors->fields as $field) {
                $validationMessages[strtolower($field)] = [$errors->message];
            }
        } else {
            $validationMessages[strtolower($errors->fields)] = [$errors->message];
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
