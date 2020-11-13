<?php

namespace A7Pro\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Cli\Dispatcher;

class CliDispatcherProvider implements ServiceProviderInterface
{
    public function register(DiInterface $container): void
    {
        $container->setShared(
            'dispatcher',
            function () {
                $dispatcher = new Dispatcher();

                return $dispatcher;
            }
        );
    }
}
