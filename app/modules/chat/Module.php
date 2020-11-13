<?php

namespace A7Pro\Chat;

use Phalcon\Di\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'A7Pro\Chat\Core\Domain\Exceptions' => __DIR__ . '/Core/Domain/Exceptions',
            'A7Pro\Chat\Core\Domain\Models' => __DIR__ . '/Core/Domain/Models',
            'A7Pro\Chat\Core\Domain\Repositories' => __DIR__ . '/Core/Domain/Repositories',
            'A7Pro\Chat\Core\Domain\Services' => __DIR__ . '/Core/Domain/Services',
            'A7Pro\Chat\Core\Application\Services' => __DIR__ . '/Core/Application/Services',
            'A7Pro\Chat\Infrastructure\Persistence' => __DIR__ . '/Infrastructure/Persistence',
            'A7Pro\Chat\Infrastructure\Services' => __DIR__ . '/Infrastructure/Services',
            'A7Pro\Chat\Presentation\Controllers' => __DIR__ . '/Presentation/Controllers',
        ]);

        $loader->register();
    }

    public function registerServices(DiInterface $container)
    {
        include __DIR__ . '/config/services.php';
    }
}
