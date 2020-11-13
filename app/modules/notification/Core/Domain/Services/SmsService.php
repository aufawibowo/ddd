<?php

namespace A7Pro\Notification\Core\Domain\Services;

interface SmsService
{
    public function send(string $phone, string $message): bool;
}
