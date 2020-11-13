<?php

namespace A7Pro\Account\Core\Domain\Services;

interface SmsService
{
    public function send(string $phone, string $message): bool;
}