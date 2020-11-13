<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Models;

class User
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_REJECTED = 'rejected';

    const ROLE_SELLER = 'seller';

    private string $id;
    private string $name;
    private ?string $username;
    private ?Email $email;
    private ?Phone $phone;
    private ?string $profilePict;
    private string $password;
    private string $status;
    private array $roles;

    public function __construct(
        string $id,
        string $name,
        ?string $username,
        ?Email $email,
        ?Phone $phone,
        ?string $profilePict,
        string $password,
        string $status,
        array $roles
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->profilePict = $profilePict;
        $this->password = $password;
        $this->status = $status;
        $this->roles = $roles;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function getProfilePict(): ?string
    {
        return $this->profilePict;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function canLogin(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function verifyPassword(string $password): bool
    {
        if (strlen($password) === 0) {
            return false;
        }

        return password_verify($password, $this->password);
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->email && !$this->email->isValid()) {
            $errors[] = 'invalid_email';
        }

        if ($this->phone && !$this->phone->isValid()) {
            $errors[] = 'invalid_phone';
        }

        return $errors;
    }
}