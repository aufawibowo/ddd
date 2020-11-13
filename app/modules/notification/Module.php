<?php

namespace A7Pro\Notification;

use Phalcon\Di\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'A7Pro\Notification\Core\Domain\Exceptions' => __DIR__ . '/Core/Domain/Exceptions',
            'A7Pro\Notification\Core\Domain\Models' => __DIR__ . '/Core/Domain/Models',
            'A7Pro\Notification\Core\Domain\Repositories' => __DIR__ . '/Core/Domain/Repositories',
            'A7Pro\Notification\Core\Domain\Services' => __DIR__ . '/Core/Domain/Services',
            'A7Pro\Notification\Core\Application\Services' => __DIR__ . '/Core/Application/Services',
            'A7Pro\Notification\Infrastructure\Persistence' => __DIR__ . '/Infrastructure/Persistence',
            'A7Pro\Notification\Infrastructure\Services' => __DIR__ . '/Infrastructure/Services',
            'A7Pro\Notification\Presentation\Controllers' => __DIR__ . '/Presentation/Controllers',
            'A7Pro\Notification\Presentation\Consoles' => __DIR__ . '/Presentation/Consoles',
        ]);

        $loader->register();
    }

    public function registerServices(DiInterface $container)
    {
        include __DIR__ . '/config/services.php';
    }
}
