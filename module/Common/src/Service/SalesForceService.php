<?php

namespace Common\Service;

use Common\Exception\ApplicationException;
use Common\Exception\SoapException;
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
     * @throws SoapException
     */
    public function getUserInfo()
    {
        $this->login();

        try {
            $info = $this->client->call('getUserInfo', ['getUserInfo' => []]);
        } catch (\Exception $e) {
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }

        return json_decode(json_encode($info), true);
    }

    /**
     * @throws SoapException
     */
    public function login()
    {
        if ($this->sessionId) {
            return;
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
            $loginResult = json_decode(json_encode($loginResult), true);
        } catch (\Exception $e) {
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }

        // Set new Url retrieve from the login
        $this->client->setUri($loginResult['result']['serverUrl']);
        $this->client->setLocation($loginResult['result']['serverUrl']);

        // attach the session id to the soap client
        $this->sessionId = $loginResult['result']['sessionId'];
        $header = new \SoapHeader(
            $this->getNamespace(),
            'SessionHeader',
            ['sessionId' => $this->sessionId]
        );
        $this->client->addSoapInputHeader($header, true);
    }

    /**
     * @throws SoapException
     */
    public function logout()
    {
        try {
            $this->client->call('logout', ['logout' => []]);
        } catch (\Exception $e) {
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }
    }

    /**
     * @param string $type
     *
     * @return array
     * @throws SoapException
     */
    public function describesObject($type)
    {
        $this->login();

        $data = new \stdClass();
        $data->sObjectType = $type;
        $object = new \SoapVar($data, SOAP_ENC_OBJECT, 'sObjectType', $this->getNamespace());
        $object = new \SoapParam($object, 'sObjectType');

        try {
            $response = $this->client->call(
                'describeSObject',
                ['describeSObject' => $object]
            );
        } catch (\Exception $e) {
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }

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
     *
     * @throws SoapException
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
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }
    }

    /**
     * @param \SoapParam $object
     * @param string     $action
     *
     * @return string
     * @throws SoapException
     */
    public function action(\SoapParam $object, $action)
    {
        $this->login();

        try {
            $response = $this->client->call(
                $action,
                [$action => $object]
            );
            $response = json_decode(json_encode($response), true);
        } catch (\Exception $e) {
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }

        // TODO Log failing error to logger
        if ($response['result']['success'] == false && isset($response['result']['errors'])) {
            $this->buildValidationErrors($response['result']['errors']);
        }

        return $response['result'];
    }

    /**
     * @param array $errors
     *
     * @throws ApplicationException
     */
    public function buildValidationErrors($errors)
    {
        $validationMessages = [];
        if (isset($errors['fields'])) {
            if (is_array($errors['fields'])) {
                foreach ($errors['fields'] as $field) {
                    $validationMessages[strtolower($field)] = [$errors['message']];
                }
            } else {
                $validationMessages[strtolower($errors['fields'])] = [$errors['message']];
            }
        } else if (!isset($errors['message'])) {
            foreach ($errors as $field) {
                $validationMessages[strtolower($field['fields'])] = [$field['message']];
            }
        } else {
            $validationMessages = $errors['message'];
        }

        throw new ApplicationException(['validation_messages' => $validationMessages]);
    }

    /**
     * @param \stdClass $query
     *
     * @return array
     * @throws SoapException
     */
    public function query(\stdClass $query)
    {
        $this->login();

        try {
            $response = $this->client->call(
                'query',
                ['query' => $query]
            );
            $response = json_decode(json_encode($response), true);
        } catch (\Exception $e) {
            throw new SoapException($e, $this->client->getLastRequest(), $this->client->getLastResponse());
        }

        return $response['result'];
    }
}
