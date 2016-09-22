<?php

namespace Mail\Factory\Service;

use Common\Service\HttpService;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

final class MailServiceFactory
{
    const CONFIG = 'config';
    const GOV_DELIVERY = 'gov-delivery';
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
        $client = $serviceManager->get(HttpService::class);
        $config = $serviceManager->get(self::CONFIG);

        $client->setServer($config[self::GOV_DELIVERY][self::SERVER]);

        $client->setHeaders([
            'X-AUTH-TOKEN' => $config[self::GOV_DELIVERY][self::TOKEN],
            'Content-type' => 'application/json',
            'Accept'       => 'application/json',
        ]);
        $client->setScheme($config[self::GOV_DELIVERY][self::SCHEME]);

        return new MailService($client);
    }
}