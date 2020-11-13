<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Services;

use A7Pro\Marketplace\Toko\Core\Domain\Services\OtpService;
use Phalcon\Config;

class HashBasedOtpService implements OtpService
{
    private $key;

    public function __construct(Config $config)
    {
        $this->key = $config->path('app.key');
    }

    public function generate(string $context): array
    {
        $otp = rand(100000, 999999);
        $ttl = 5 * 60; // 5 minutes
        $expires = time() + $ttl;

        $data = $context . $otp . $expires;
        $hash = hash_hmac('sha256', $data, $this->key);
        $hash = $hash . '.' . $expires;

        return [$otp, $hash];
    }

    public function verify(string $otp, string $context, string $hash): bool
    {
        $tmp = explode(".", $hash);
        $hash = $tmp[0];
        $expires = $tmp[1];
        $now = time();

        $data = $context . $otp . $expires;
        $hashed = hash_hmac('sha256', $data, $this->key);

        return hash_equals($hashed, $hash) && !($now > $expires);
    }
}