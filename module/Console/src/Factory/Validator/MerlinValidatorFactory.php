<?php

namespace Console\Factory\Validator;

use Console\Validator\MerlinValidator;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class MerlinValidatorFactory
{
    const CONFIG = 'config';

    const MERLIN_DATA_STRUCTURE = 'merlin-data-structure';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return MerlinValidator
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(self::CONFIG);
        $logger = $serviceManager->get(Logger::class);

        if (array_key_exists(self::MERLIN_DATA_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }

        return new MerlinValidator($logger, $config[self::MERLIN_DATA_STRUCTURE]);
    }
}
