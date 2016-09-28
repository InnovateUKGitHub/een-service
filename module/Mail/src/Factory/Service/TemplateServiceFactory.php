<?php

namespace Mail\Factory\Service;

use Common\Constant\EEN;
use Common\Service\HttpService;
use Mail\Service\TemplateService;
use Zend\ServiceManager\ServiceManager;

final class TemplateServiceFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return TemplateService
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

        return new TemplateService($client);
    }
}