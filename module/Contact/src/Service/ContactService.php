<?php

namespace Contact\Service;

use Console\Service\HttpService;

class ContactService
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

    public function create($params)
    {
    }
}
