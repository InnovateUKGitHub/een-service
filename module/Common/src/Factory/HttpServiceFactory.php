<?php

namespace Common\Factory;

use Common\Service\HttpService;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\ServiceManager\ServiceManager;

final class HttpServiceFactory
{
    const CONFIG = 'config';
    const CURL = 'curl-opt';
    const ENCODING = 'encoding';
    const MAX_CONNECTION = 'max-connection';
    const FRESH_CONNECTION = 'fresh-connection';
    const TIMEOUT = 'timeout';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return HttpService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(self::CONFIG);
        $config = $config[self::CURL];

        $adapter = new Curl();
        $adapter->setCurlOption(CURLOPT_ENCODING, $config[self::ENCODING]);
        $adapter->setOptions([
            CURLOPT_MAXCONNECTS   => $config[self::MAX_CONNECTION],
            CURLOPT_FRESH_CONNECT => $config[self::FRESH_CONNECTION],
        ]);
        $adapter->setOptions(['timeout' => $config[self::TIMEOUT]]);
        $client = new Client(null, ['timeout' => $config[self::TIMEOUT]]);
        $client->setAdapter($adapter);

        return new HttpService($client);
    }
}
