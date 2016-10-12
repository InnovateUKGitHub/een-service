<?php

namespace Console\Factory\Service\Event;

use Common\Constant\EEN;
use Common\Factory\HttpServiceFactory;
use Console\Service\Event\MerlinConnection;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

final class MerlinConnectionFactory
{
    /**
     * @param ServiceManager $serviceManager
     *
     * @return MerlinConnection
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $client = (new HttpServiceFactory())->__invoke($serviceManager);
        $config = $serviceManager->get(EEN::CONFIG);

        $this->checkRequiredConfig($config);

        $client->setServer($config[EEN::MERLIN][EEN::SERVER]);

        $client->setHeaders([
            'Content-type' => 'application/xml',
            'Accept'       => 'application/xml',
        ]);

        $logger = $serviceManager->get(Logger::class);

        $config = $config[EEN::MERLIN];

        return new MerlinConnection(
            $client,
            $logger,
            $config[EEN::USERNAME],
            $config[EEN::PASSWORD],
            $config[EEN::PATH_EVENT]
        );
    }

    /**
     * @param array $config
     */
    private function checkRequiredConfig($config)
    {
        // Test if the require keys are present in the configuration
        if (array_key_exists(EEN::MERLIN, $config) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the merlin information');
        }
        if (array_key_exists(EEN::SERVER, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the server');
        }

        if (array_key_exists(EEN::USERNAME, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the username');
        }
        if (array_key_exists(EEN::PASSWORD, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the password');
        }
        if (array_key_exists(EEN::PATH_EVENT, $config[EEN::MERLIN]) === false) {
            throw new InvalidArgumentException('The config file is incorrect. Please specify the path_get_event');
        }
    }
}
