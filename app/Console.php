<?php

use Phalcon\Cli\Console as BaseConsole;
use Phalcon\Di\FactoryDefault\Cli as CliDI;

class Console extends BaseConsole
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

        $this->registerModules($this->_modules);
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
        $container = new CliDI();

        $services = include APP_PATH . '/config/cli-providers.php';

        foreach ($services as $service) {
            $container->register(new $service());
        }

        $this->setDI($container);
    }

    public function handleError(\Exception $e)
    {
        echo "Error: (" . $e->getCode() . ')' . $e->getMessage();
    }
}
