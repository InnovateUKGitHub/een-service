<?php

namespace Contact\Factory\Controller;

use Contact\Controller\DescribeController;
use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;

final class DescribeControllerFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return DescribeController
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $service = $serviceManager->get(ContactService::class);

        return new DescribeController($service);
    }
}
