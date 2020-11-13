<?php

namespace A7Pro\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Router;

class RouterProvider implements ServiceProviderInterface
{
    public function register(DiInterface $container): void
    {
        $container->setShared(
            'router',
            function () {
                $modules = require APP_PATH . '/config/modules.php';

                $router = new Router(false);

                foreach ($modules as $moduleName => $module) {
                    $routes = require $module['routePath'];

                    foreach ($routes as $route) {
                        $router->add($route['pattern'], [
                            'module' => $moduleName,
                            'namespace' => $route['namespace'],
                            'controller' => $route['controller'],
                            'action' => $route['action']
                        ], $route['method']);
                    }
                }

                $router->removeExtraSlashes(true);

                return $router;
            }
        );
    }
}
