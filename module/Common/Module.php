<?php

namespace Common;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class Module implements ConfigProviderInterface, AutoloaderProviderInterface, BootstrapListenerInterface
{
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

    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'onRenderError'], 0);
    }

    /**
     * @param MvcEvent $e
     *
     * @return null|JsonModel
     */
    public function onDispatchError(MvcEvent $e)
    {
        return $this->getJsonModelError($e);
    }

    /**
     * @param MvcEvent $e
     *
     * @return JsonModel
     */
    public function onRenderError(MvcEvent $e)
    {
        return $this->getJsonModelError($e);
    }

    /**
     * @param MvcEvent $e
     *
     * @return JsonModel
     */
    public function getJsonModelError(MvcEvent $e)
    {
        $error = $e->getError();
        if (!$error) {
            return new JsonModel();
        }

        $exception = $e->getParam('exception');
        $exceptionJson = [];
        if ($exception) {
            $exceptionJson = [
                'class'      => get_class($exception),
                'file'       => $exception->getFile(),
                'line'       => $exception->getLine(),
                'message'    => $exception->getMessage(),
                'stacktrace' => $exception->getTraceAsString(),
            ];
        }

        $errorJson = [
            'message'   => 'An error occurred during execution; please try again later.',
            'error'     => $error,
            'exception' => $exceptionJson,
        ];
        if ($error == 'error-router-no-match') {
            $errorJson['message'] = 'Resource not found.';
        }

        $model = new JsonModel(['errors' => [$errorJson]]);

        $e->setResult($model);

        return $model;
    }
}
