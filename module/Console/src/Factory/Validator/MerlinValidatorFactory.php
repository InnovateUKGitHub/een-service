<?php

namespace Console\Factory\Validator;

use Console\Validator\MerlinValidator;
use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class MerlinValidatorFactory implements FactoryInterface
{
    const CONFIG = 'config';

    const MERLIN_DATA_STRUCTURE = 'merlin-data-structure';

    /**
     * {@inheritDoc}
     *
     * @return MerlinValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(self::CONFIG);
        $logger = $serviceLocator->get(Logger::class);

        if (array_key_exists(self::MERLIN_DATA_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }

        return new MerlinValidator($logger, $config[self::MERLIN_DATA_STRUCTURE]);
    }
}
