<?php

namespace A7Pro\Marketplace\Customer;

use Phalcon\Di\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'A7Pro\Marketplace\Customer\Core\Domain\Exceptions' => __DIR__ . '/Core/Domain/Exceptions',
            'A7Pro\Marketplace\Customer\Core\Domain\Models' => __DIR__ . '/Core/Domain/Models',
            'A7Pro\Marketplace\Customer\Core\Domain\Repositories' => __DIR__ . '/Core/Domain/Repositories',
            'A7Pro\Marketplace\Customer\Core\Domain\Services' => __DIR__ . '/Core/Domain/Services',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Cart' => __DIR__ . '/Core/Application/Services/Cart',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Invoice' => __DIR__ . '/Core/Application/Services/Invoice',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Order' => __DIR__ . '/Core/Application/Services/Order',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Pay' => __DIR__ . '/Core/Application/Services/Pay',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Product' => __DIR__ . '/Core/Application/Services/Product',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Profile' => __DIR__ . '/Core/Application/Services/Profile',
            'A7Pro\Marketplace\Customer\Core\Application\Services\Review' => __DIR__ . '/Core/Application/Services/Review',
            'A7Pro\Marketplace\Customer\Infrastructure\Persistence' => __DIR__ . '/Infrastructure/Persistence',
            'A7Pro\Marketplace\Customer\Infrastructure\Services' => __DIR__ . '/Infrastructure/Services',
            'A7Pro\Marketplace\Customer\Presentation\Controllers' => __DIR__ . '/Presentation/Controllers',
        ]);

        $loader->register();
    }

    public function registerServices(DiInterface $container)
    {
        include __DIR__ . '/config/services.php';
    }
}
