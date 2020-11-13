<?php

namespace A7Pro\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Dispatcher;

class DispatcherProvider implements ServiceProviderInterface
{
    public function register(DiInterface $container): void
    {
        $container->setShared(
            'dispatcher',
            function () use ($container) {
                $eventsManager = $container->getShared('eventsManager');

                $dispatcher = new Dispatcher();
                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            }
        );
    }
}
