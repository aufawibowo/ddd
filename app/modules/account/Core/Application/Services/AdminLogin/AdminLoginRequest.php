<?php

namespace A7Pro\Account\Core\Application\Services\AdminLogin;

class AdminLoginRequest
{
    public ?string $id;
    public ?string $password;

    public function __construct(?string $id, ?string $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->id)) {
            $errors[] = 'id_must_be_specified';
        }

        if (!isset($this->password)) {
            $errors[] = 'password_must_be_specified';
        }

        return $errors;
    }
}