<?php

namespace A7Pro\Account\Core\Domain\Services;

interface UrlSignerService
{
    public function sign(string $url, \DateTime $expiration): string;
    public function validate(string $url): bool;
}