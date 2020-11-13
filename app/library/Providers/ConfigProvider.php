<?php

namespace A7Pro\Providers;

use Dotenv\Dotenv;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface
{
    public function register(DiInterface $container): void
    {
        $container->setShared(
            'config',
            function () {
                $dotEnv = Dotenv::createMutable(APP_PATH, '.env');
                $dotEnv->load();

                $config = require APP_PATH . '/config/config.php';

                return $config;
            }
        );
    }
}
