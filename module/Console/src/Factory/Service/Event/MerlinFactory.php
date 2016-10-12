<?php

namespace Console\Factory\Service\Event;

use Common\Constant\EEN;
use Console\Service\Event\MerlinConnection;
use Console\Service\Event\Merlin;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;
use Zend\ServiceManager\ServiceManager;

final class MerlinFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return Merlin
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $indexService = $serviceManager->get(IndexService::class);
        $merlinData = $serviceManager->get(MerlinConnection::class);
        $merlinValidator = $serviceManager->get(MerlinValidator::class);

        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        return new Merlin($indexService, $merlinData, $merlinValidator, $config[EEN::MERLIN_EVENT_STRUCTURE]);
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
