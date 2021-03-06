<?php

namespace SyncTest;

use Zend\Console\Console;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\Util\ModuleLoader;

class Bootstrap
{
    /** @var Bootstrap */
    protected static $instance;
    protected $configPath = '/../../../config/application.config.php';
    /** @var  ServiceManager */
    protected $serviceManager;

    /**
     * Return service manager (create if not exists)
     *
     * @param bool|false $isolated If true, create new instance of service manager (isolated)
     *
     * @return ServiceManager
     */
    public static function getServiceManager($isolated = false)
    {
        $instance = self::getInstance($isolated);

        if ($instance->serviceManager === null) {
            $instance->createServiceManager();
        }

        return $instance->serviceManager;
    }

    /**
     * Get the singleton instance
     *
     * @param bool|false $isolated If true, create new instance of service manager (isolated)
     *
     * @return Bootstrap
     */
    protected static function getInstance($isolated = false)
    {
        if (static::$instance === null || $isolated === true) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Create service manager
     *
     * @return ServiceManager
     */
    protected function createServiceManager()
    {
        Console::overrideIsConsole(false);

        $applicationConfig = $this->getPathToCfg();
        $applicationConfig['module_listener_options']['config_cache_enabled'] = false;

        $this->serviceManager = (new ModuleLoader($applicationConfig))->getServiceManager();

        return $this->serviceManager;
    }

    protected function getPathToCfg()
    {
        return require __DIR__ . $this->configPath;
    }
}