<?php

namespace Console;

use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\Application;
use Zend\Mvc\Console\View\ViewModel;
use Zend\Mvc\MvcEvent;

class Module implements
    ConfigProviderInterface,
    ConsoleUsageProviderInterface,
    ConsoleBannerProviderInterface,
    AutoloaderProviderInterface,
    BootstrapListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'Importation Script EEN';
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return require __DIR__ . '/config/console-usage.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function OnBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        /** @var Application $application */
        $application = $e->getApplication();

        $application->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            function(MvcEvent $e) {
                /** @var ViewModel $result */
                $result = $e->getResult();

                echo $result->getVariable('result');
            }
        );
    }
}
