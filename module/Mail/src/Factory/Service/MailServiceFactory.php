<?php

namespace Mail\Factory\Service;

use Common\Factory\HttpServiceFactory;
use Common\Service\HttpService;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

final class MailServiceFactory
{
    const CONFIG_SERVICE = 'config';
    const CONFIG_TMS = 'gov-delivery';
    const SERVER = 'server';
    const SCHEME = 'scheme';
    const TOKEN = 'token';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return MailService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /** @var HttpService $query */
        $client = (new HttpServiceFactory())->__invoke($serviceManager);
        $config = $serviceManager->get(self::CONFIG_SERVICE);

        $client->setServer($config[self::CONFIG_TMS][self::SERVER]);

        $client->setHeaders([
            'X-AUTH-TOKEN' => $config[self::CONFIG_TMS][self::TOKEN],
            'Content-type' => 'application/json',
            'Accept'       => 'application/json',
        ]);
        $client->setScheme($config[self::CONFIG_TMS][self::SCHEME]);

        return new MailService($client);
    }
}