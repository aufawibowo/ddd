<?php

namespace A7Pro\Chat\Core\Domain\Models;

class User
{
    public string $id;
    public ?string $name;
    public ?string $profilePict;

    public function __construct(
        string $id,
        ?string $name,
        ?string $profilePict
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->profilePict = $profilePict;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProfilePict(): ?string
    {
        return $this->profilePict;
    }
}