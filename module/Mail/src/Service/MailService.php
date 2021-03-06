<?php

namespace Mail\Service;

use Common\Service\HttpService;
use Zend\Http\Request;

class MailService
{
    /** @var HttpService */
    private $client;

    /**
     * MailService constructor.
     *
     * @param HttpService $client
     */
    public function __construct(HttpService $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function send($data)
    {
        return $this->client->execute(Request::METHOD_POST, '/messages/email', [], $data);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function get($id)
    {
        return $this->client->execute(Request::METHOD_GET, '/messages/email/' . $id);
    }
}
