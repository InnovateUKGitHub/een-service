<?php

namespace Console\Factory\Service\Event;

use Console\Service\Event\MerlinIngest;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

final class MerlinIngestFactory
{
    const CONFIG = 'config';
    const MERLIN_DATA_STRUCTURE = 'merlin-event-structure';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return MerlinIngest
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $merlinValidator = $serviceManager->get(MerlinValidator::class);

        $config = $serviceManager->get(self::CONFIG);

        if (array_key_exists(self::MERLIN_DATA_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }

        return new MerlinIngest($indexService, $merlinValidator, $config[self::MERLIN_DATA_STRUCTURE]);
    }
}
