<?php

namespace Console\Factory\Service\Event;

use Common\Constant\EEN;
use Console\Service\Event\MerlinIngest;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

final class MerlinIngestFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return MerlinIngest
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $merlinValidator = $serviceManager->get(MerlinValidator::class);

        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        return new MerlinIngest($indexService, $merlinValidator, $config[EEN::MERLIN_EVENT_STRUCTURE]);
    }
    /**
     * @param array $config
     */
    private function checkRequiredConfig($config)
    {
        if (array_key_exists(EEN::MERLIN_EVENT_STRUCTURE, $config) === false) {
            throw new \InvalidArgumentException('The config file is incorrect. Please specify the merlin data structure');
        }
    }
}
