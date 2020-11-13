<?php

namespace A7Pro\Account\Core\Domain\Services;

interface TokenService
{
    public function encode($payload): string;
    public function decode($token): array;
}