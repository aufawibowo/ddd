<?php

use Phalcon\Exception as PhalconException;

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

require __DIR__ . '/vendor/autoload.php';

require_once APP_PATH . '/Console.php';

$console = new Console();
$console->initialize();

$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['module'] = $arg;
    } elseif ($k === 2) {
        $arguments['task'] = $arg;
    } elseif ($k === 3) {
        $arguments['action'] = $arg;
    } elseif ($k >= 4) {
        $arguments['params'][] = $arg;
    }
}

$dispatcher = $console->getDI()->get('dispatcher');
$dispatcher->setNamespaceName($console->getModule($arguments["module"])["tasksNamespace"]);

try {
    $console->handle($arguments);
} catch (PhalconException $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
} catch (Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
    exit(1);
} catch (Exception $exception) {
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
