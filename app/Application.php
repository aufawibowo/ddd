<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;
use Phalcon\Mvc\Dispatcher;

class Application extends BaseApplication
{
    private $_modules;

    public function __construct()
    {
        $this->_modules = require_once APP_PATH . '/config/modules.php';
    }

    public function initialize()
    {
        $this->_registerNameSpaces();
        $this->_registerServices();
        $this->_registerListeners();

        $this->registerModules($this->_modules);
        $this->useImplicitView(false);
    }

    private function _registerNameSpaces()
    {
        $loader = new Phalcon\Loader();

        $loader->registerNamespaces([
            'A7Pro\Controllers' => APP_PATH . "/library/Controllers",
            'A7Pro\Providers' => APP_PATH . "/library/Providers",
            'A7Pro\Listeners' => APP_PATH . "/library/Listeners",
            'A7Pro\Traits' => APP_PATH . "/library/Traits",
        ]);

        $loader->register();
    }

    private function _registerServices()
    {
        $container = new FactoryDefault();

        $services = include APP_PATH . '/config/providers.php';

        foreach ($services as $service) {
            $container->register(new $service());
        }

        $this->setDI($container);
    }

    private function _registerListeners()
    {
        $container = $this->getDI();
        $eventsManager = $container->getShared('eventsManager');

        $listeners = include APP_PATH . '/config/listeners.php';

        foreach ($listeners as $listener) {
            $eventsManager->attach($listener['eventType'], new $listener['handler']);
        }

        $this->setEventsManager($eventsManager);
    }

    public function handleError(\Exception $e)
    {
        echo "Error: (" . $e->getCode() . ')' . $e->getMessage();
        echo get_class($e), ': ', $e->getMessage(), '\n';
        echo ' File=', $e->getFile(), '\n';
        echo ' Line=', $e->getLine(), '\n';
        echo $e->getTraceAsString();
    }
}
