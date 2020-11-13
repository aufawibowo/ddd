<?php

use A7Pro\Account\Infrastructure\Services\JwtTokenService;
use A7Pro\Account\Infrastructure\Services\HashBasedOtpService;
use A7Pro\Account\Infrastructure\Services\GoSmsService;
use A7Pro\Account\Infrastructure\Services\Md5UrlSignerService;
use A7Pro\Account\Infrastructure\Services\ApiApituService;
use A7Pro\Account\Infrastructure\Persistence\SqlDpcRepository;
use A7Pro\Account\Infrastructure\Persistence\SqlUserRepository;

$container->setShared('tokenService', function() use ($container) {
    return new JwtTokenService($container->get('config'));
});

$container->setShared('otpService', function () use($container) {
    return new HashBasedOtpService($container->get('config'));
});

$container->setShared('smsService', function () use($container) {
    return new GoSmsService($container->get('config'));
});

$container->setShared('urlSignerService', function () use($container) {
    return new Md5UrlSignerService($container->get('config'));
});

$container->setShared('apituService', function() use ($container) {
    return new ApiApituService($container->get('config'));
});

$container->setShared('dpcRepository', function() use ($container) {
    return new SqlDpcRepository($container->get('db'));
});

$container->setShared('userRepository', function() use ($container) {
    return new SqlUserRepository($container->get('db'));
});