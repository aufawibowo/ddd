<?php

namespace A7Pro\Account\Infrastructure\Services;

use A7Pro\Account\Core\Domain\Services\TokenService;
use Firebase\JWT\JWT;
use Phalcon\Config;

class JwtTokenService implements TokenService
{
    private $key;

    public function __construct(Config $config)
    {
        $this->key = $config->path('app.key');
    }

    public function encode($payload): string
    {
        return JWT::encode([
            'iss' => "http://a7pro.id",
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + (60 * 60 * 24 * 7),
            'sub' => $payload
        ], $this->key);
    }

    public function decode($token): array
    {
        return (array) JWT::decode($token, $this->key, ['HS256']);
    }
}