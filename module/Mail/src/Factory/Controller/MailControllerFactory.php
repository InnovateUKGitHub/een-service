<?php

namespace Mail\Factory\Controller;

use Mail\Controller\MailController;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

final class MailControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return MailController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /** @var MailService $service */
        $service = $serviceManager->get(MailService::class);

        return new MailController($service);
    }
}
