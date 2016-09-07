<?php

namespace Console\Factory\Validator;

use Console\Validator\MerlinValidator;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class MerlinValidatorFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return MerlinValidator
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $logger = $serviceManager->get(Logger::class);

        return new MerlinValidator($logger);
    }
}
