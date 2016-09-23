<?php

namespace Mail\Factory\Service;

use Common\Constant\EEN;
use Common\Service\HttpService;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

final class MailServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return MailService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $client = $serviceManager->get(HttpService::class);
        $config = $serviceManager->get(EEN::CONFIG);
        $config = $config[EEN::GOV_DELIVERY];

        $client->setServer($config[EEN::SERVER]);
        $client->setHeaders([
            'X-AUTH-TOKEN' => $config[EEN::TOKEN],
            'Content-type' => 'application/json',
            'Accept'       => 'application/json',
        ]);
        $client->setScheme($config[EEN::SCHEME]);

        return new MailService($client);
    }
}