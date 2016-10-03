<?php

namespace Contact\Factory\Controller;

use Contact\Controller\EmailController;
use Mail\Service\MailService;
use Zend\ServiceManager\ServiceManager;

final class EmailControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return EmailController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(MailService::class);

        return new EmailController($service);
    }
}
