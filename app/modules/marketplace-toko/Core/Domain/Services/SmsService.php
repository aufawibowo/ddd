<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Services;

interface SmsService
{
    public function send(string $phone, string $message): bool;
}