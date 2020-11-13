<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Services;

interface UrlSignerService
{
    public function sign(string $url, \DateTime $expiration): string;
    public function validate(string $url): bool;
}