<?php

namespace A7Pro\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Predis\Client;

class RedisProvider implements ServiceProviderInterface
{
    public function register(DiInterface $container): void
    {
        $container->setShared(
            'redis',
            function () use ($container) {
                $config = $container->getShared('config');

                return new Client([
                    'scheme' => 'tcp',
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port')
                ]);
            }
        );
    }
}