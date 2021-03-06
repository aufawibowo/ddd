<?php

namespace A7Pro\Account;

use Phalcon\Db\Adapter\PdoFactory;
use Phalcon\Loader;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(
        DiInterface $container = null
    ) {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                'A7Pro\Account\Core\Domain\Models' => __DIR__ . '/Core/Domain/Models',
                'A7Pro\Account\Core\Domain\Repositories' => __DIR__ . '/Core/Domain/Repositories',
                'A7Pro\Account\Core\Domain\Services' => __DIR__ . '/Core/Domain/Services',
                'A7Pro\Account\Core\Domain\Exceptions' => __DIR__ . '/Core/Domain/Exceptions',
                'A7Pro\Account\Core\Application\Services' => __DIR__ . '/Core/Application/Services',
                'A7Pro\Account\Infrastructure\Persistence' => __DIR__ . '/Infrastructure/Persistence',
                'A7Pro\Account\Infrastructure\Services' => __DIR__ . '/Infrastructure/Services',
                'A7Pro\Account\Presentation\Controllers' => __DIR__ . '/Presentation/Controllers',
            ]
        );

        $loader->register();
    }

    public function registerServices(DiInterface $container)
    {
        include __DIR__ . '/config/services.php';
    }
}
