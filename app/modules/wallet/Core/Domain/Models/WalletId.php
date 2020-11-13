<?php

namespace A7Pro\Wallet\Core\Domain\Models;

use Ramsey\Uuid\Uuid;

class WalletId
{
    private string $id;

    public function __construct(string $id = null)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function isValid(): bool
    {
        return Uuid::isValid($this->id);
    }
}