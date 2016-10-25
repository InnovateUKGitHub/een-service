<?php

namespace Common\Service;

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
     * SalesForce constructor.
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

    /**
     * @return bool
     */
    public function login()
    {
        if ($this->sessionId) {
            return true;
        }

        try {
            $loginResult = $this->client->call(
                'login',
                [
                    'login' => [
                        'username' => $this->username,
                        'password' => $this->password,
                    ],
                ]
            );
        } catch (\Exception $e) {
            // TODO Log failing error to logger
            return false;
        }

        // Set new Url retrieve from the login
        $this->client->setUri($loginResult->result->serverUrl);
        $this->client->setLocation($loginResult->result->serverUrl);

        // attach the session id to the soap client
        $this->sessionId = $loginResult->result->sessionId;
        $header = new \SoapHeader(
            $this->getNamespace(),
            'SessionHeader',
            ['sessionId' => $this->sessionId]
        );
        $this->client->addSoapInputHeader($header, true);

        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        try {
            $this->client->call('logout', ['logout' => []]);
        } catch (\Exception $e) {
            // TODO Log failing error to logger
            return false;
        }

        return true;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function describesObject($type)
    {
        $this->login();

        $data = new \stdClass();
        $data->sObjectType = $type;
        $object = new \SoapVar($data, SOAP_ENC_OBJECT, 'sObjectType', $this->getNamespace());
        $object = new \SoapParam($object, 'sObjectType');

        $response = $this->client->call(
            'describeSObject',
            ['describeSObject' => $object]
        );

        $results = [];
        foreach ($response->result->fields as $field) {
            $results[$field->name] = [
                'name' => $field->name,
                'type' => $field->type,
            ];
        }

        return $results;
    }

    /**
     * @param array $ids
     */
    public function delete($ids)
    {
        $this->login();

        try {
            $this->client->call(
                'delete',
                ['delete' => $ids]
            );
        } catch (\Exception $e) {
            // TODO Log failing error to logger
        }
    }

    /**
     * @param \SoapParam $object
     * @param string     $action
     *
     * @return string|ApiProblemResponse
     */
    public function action(\SoapParam $object, $action)
    {
        $this->login();

        try {
            $response = $this->client->call(
                $action,
                [$action => $object]
            );
        } catch (\Exception $e) {
            return new ApiProblemResponse(
                new ApiProblem(
                    Response::STATUS_CODE_500,
                    'Invalid Soap answer',
                    null,
                    null,
                    [
                        'code'      => $e->getCode(),
                        'exception' => $e->getMessage(),
                        'request'   => $this->client->getLastRequest(),
                        'response'  => $this->client->getLastResponse(),
                    ]
                )
            );
        }
        // TODO Log failing error to logger
        if ($response->result->success == false && isset($response->result->errors)) {
            return $this->buildValidationErrors($response->result->errors);
        }

        return $response->result->id;
    }

    /**
     * @param \stdClass $errors
     *
     * @return ApiProblemResponse
     */
    public function buildValidationErrors($errors)
    {
        $validationMessages = [];
        if (is_array($errors)) {
            foreach ($errors as $field) {
                $validationMessages[strtolower($field->fields)] = [$field->message];
            }
        } else if (isset($errors->fields)) {
            if (is_array($errors->fields)) {
                foreach ($errors->fields as $field) {
                    $validationMessages[strtolower($field)] = [$errors->message];
                }
            } else {
                $validationMessages[strtolower($errors->fields)] = [$errors->message];
            }
        } else {
            $validationMessages = $errors->message;
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

    /**
     * @param \stdClass $query
     *
     * @return ApiProblemResponse
     */
    public function query(\stdClass $query)
    {
        $this->login();

        try {
            $response = $this->client->call(
                'query',
                ['query' => $query]
            );
        } catch (\Exception $e) {
            return new ApiProblemResponse(
                new ApiProblem(
                    Response::STATUS_CODE_500,
                    'Invalid Soap answer',
                    null,
                    null,
                    [
                        'code'      => $e->getCode(),
                        'exception' => $e->getMessage(),
                        'request'   => $this->client->getLastRequest(),
                        'response'  => $this->client->getLastResponse(),
                    ]
                )
            );
        }

        return $response->result;
    }
}
