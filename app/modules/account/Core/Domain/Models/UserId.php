<?php

namespace A7Pro\Account\Core\Domain\Models;

use Ramsey\Uuid\Uuid;

class UserId
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
}