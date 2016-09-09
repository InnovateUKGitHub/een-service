<?php

namespace Console\Factory\Service;

use Console\Service\HttpService;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\ServiceManager\ServiceManager;

final class HttpServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return HttpService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $adapter = new Curl();
        $adapter->setCurlOption(CURLOPT_ENCODING, 'deflate');
        $adapter->setOptions([
            CURLOPT_MAXCONNECTS   => 3,
            CURLOPT_FRESH_CONNECT => true,
        ]);
        $adapter->setOptions(['timeout' => 300]);
        $client = new Client(null, ['timeout' => 300]);
        $client->setAdapter($adapter);

        return new HttpService($client);
    }
}
