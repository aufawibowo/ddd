<?php

namespace A7Pro\Account\Core\Domain\Models;

class ZipCode
{
    private string $zipCode;

    public function __construct(string $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function zipCode(): string
    {
        return $this->zipCode;
    }
}