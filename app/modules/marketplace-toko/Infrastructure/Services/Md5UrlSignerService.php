<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Services;

use A7Pro\Marketplace\Toko\Core\Domain\Services\UrlSignerService;
use Phalcon\Config;
use Spatie\UrlSigner\MD5UrlSigner;

class Md5UrlSignerService implements UrlSignerService
{
    private $key;
    private $urlSigner;

    public function __construct(Config $config)
    {
        $this->key = $config->path('app.key');
        $this->urlSigner = new MD5UrlSigner($this->key);
    }

    public function sign(string $url, \DateTime $expiration): string
    {
        return $this->urlSigner->sign($url, $expiration);
    }

    public function validate(string $url): bool
    {
        return $this->urlSigner->validate($url);
    }
}