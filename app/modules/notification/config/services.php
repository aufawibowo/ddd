<?php

use A7Pro\Notification\Infrastructure\Services\GoSmsService;
use A7Pro\Notification\Infrastructure\Services\PHPMailerEmailService;

$container->setShared('smsService', function () use ($container) {
    return new GoSmsService($container->get('config'));
});

$container->setShared('emailService', function () use ($container) {
    return new PHPMailerEmailService($container->get('config'));
});