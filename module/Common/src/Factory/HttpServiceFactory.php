<?php

namespace Common\Factory;

use Common\Constant\EEN;
use Common\Service\HttpService;
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
        $config = $serviceManager->get(EEN::CONFIG);
        $config = $config[EEN::CURL];

        $adapter = new Curl();
        $adapter->setOptions([
            CURLOPT_MAXCONNECTS   => $config[EEN::MAX_CONNECTION],
            CURLOPT_FRESH_CONNECT => $config[EEN::FRESH_CONNECTION],
            CURLOPT_TIMEOUT       => $config[EEN::TIMEOUT],
        ]);

        $client = new Client(null, ['timeout' => $config[EEN::TIMEOUT]]);
        $client->setAdapter($adapter);

        return new HttpService($client);
    }
}
