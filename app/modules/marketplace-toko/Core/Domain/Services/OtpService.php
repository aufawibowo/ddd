<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Services;

interface OtpService
{
    public function generate(string $context): array;
    public function verify(string $otp, string $context, string $hash): bool;
}