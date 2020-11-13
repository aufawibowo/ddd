<?php

namespace A7Pro\Providers;

use Phalcon\Db\Adapter\PdoFactory;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class DatabaseProvider implements ServiceProviderInterface
{
    public function register(DiInterface $container): void
    {
        $container->setShared(
            'db',
            function () use ($container) {
                $config = $container->getShared('config');

                $dbConfig = $config->database;
                $factory = new PdoFactory();

                return $factory->newInstance($dbConfig->adapter, [
                    'host' => $dbConfig->host,
                    'port' => $dbConfig->port,
                    'username' => $dbConfig->username,
                    'password' => $dbConfig->password,
                    'dbname' => $dbConfig->name,
                ]);
            }
        );
    }
}
