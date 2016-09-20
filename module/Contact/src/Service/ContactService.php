<?php

namespace Contact\Service;

use Zend\Soap\Client;

class ContactService
{
    /** @var Client */
    private $client;

    /**
     * MailService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create($params)
    {
        $this->client->login();
        var_dump($this->client->getServerTimestamp());
        $this->client->logout();
        return $params;
    }

    public function get($id)
    {
        return $id;
    }
}
