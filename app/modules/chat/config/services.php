<?php

use A7Pro\Chat\Infrastructure\Services\JwtTokenService;
use A7Pro\Chat\Infrastructure\Persistence\SqlChatRepository;
use A7Pro\Chat\Infrastructure\Persistence\SqlUserRepository;

$container->setShared('tokenService', function () use ($container) {
    return new JwtTokenService($container->get('config'));
});

$container->setShared('chatRepository', function () use ($container) {
    return new SqlChatRepository($container->get('db'));
});

$container->setShared('userRepository', function () use ($container) {
    return new SqlUserRepository($container->get('db'));
});
