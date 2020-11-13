<?php

return [
    'account' => [
        'namespace' => 'A7Pro\Account',
        'className' => A7Pro\Account\Module::class,
        'path' => APP_PATH . '/modules/account/Module.php',
        'controllerNamespace' => 'A7Pro\Account\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/account/Presentation/routes/api.php'
    ],
    'apitu' => [
        'namespace' => 'A7Pro\Apitu',
        'className' => A7Pro\Apitu\Module::class,
        'path' => APP_PATH . '/modules/apitu/Module.php',
        'controllerNamespace' => 'A7Pro\apitu\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/apitu/Presentation/routes/api.php'
    ],
    'jasa' => [
        'namespace' => 'A7Pro\Jasa',
        'className' => A7Pro\Jasa\Module::class,
        'path' => APP_PATH . '/modules/jasa/Module.php',
        'controllerNamespace' => 'A7Pro\Jasa\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/jasa/Presentation/routes/api.php'
    ],
    'notification' => [
        'namespace' => 'A7Pro\Notification',
        'className' => A7Pro\Notification\Module::class,
        'path' => APP_PATH . '/modules/notification/Module.php',
        'controllerNamespace' => 'A7Pro\Notification\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/Notification/Presentation/routes/api.php',
        'tasksNamespace' => 'A7Pro\Notification\Presentation\Consoles',
    ],
    'wallet' => [
        'namespace' => 'A7Pro\Wallet',
        'className' => A7Pro\Wallet\Module::class,
        'path' => APP_PATH . '/modules/wallet/Module.php',
        'controllerNamespace' => 'A7Pro\Wallet\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/wallet/Presentation/routes/api.php'
    ],
    'chat' => [
        'namespace' => 'A7Pro\Chat',
        'className' => A7Pro\Chat\Module::class,
        'path' => APP_PATH . '/modules/chat/Module.php',
        'controllerNamespace' => 'A7Pro\Chat\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/chat/Presentation/routes/api.php'
    ],
    'marketplace-customer' => [
        'namespace' => 'A7Pro\Marketplace\Customer',
        'className' => A7Pro\Marketplace\Customer\Module::class,
        'path' => APP_PATH . '/modules/marketplace-customer/Module.php',
        'controllerNamespace' => 'A7Pro\Marketplace\Customer\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/marketplace-customer/Presentation/routes/api.php'
    ],
    'marketplace-toko' => [
        'namespace' => 'A7Pro\Marketplace\Toko',
        'className' => A7Pro\Marketplace\Toko\Module::class,
        'path' => APP_PATH . '/modules/marketplace-toko/Module.php',
        'controllerNamespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'routePath' => APP_PATH . '/modules/marketplace-toko/Presentation/routes/api.php'
    ],
];
