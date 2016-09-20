<?php

namespace Contact\Factory\Controller;

use Contact\Controller\ContactController;
use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;

final class ContactControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return ContactController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(ContactService::class);

        return new ContactController($service);
    }
}
