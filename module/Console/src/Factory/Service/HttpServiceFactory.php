<?php

namespace Console\Factory\Service;

use Console\Service\HttpService;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class HttpServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return HttpService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = new Curl();
        $adapter->setCurlOption(CURLOPT_ENCODING, 'deflate');
        $adapter->setOptions([
            CURLOPT_MAXCONNECTS   => 3,
            CURLOPT_FRESH_CONNECT => true,
        ]);
        $adapter->setOptions(['timeout' => 30]);
        $client = new Client(null, ['timeout' => 30]);
        $client->setAdapter($adapter);

        return new HttpService($client);
    }
}
