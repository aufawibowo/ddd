<?php

namespace A7Pro\Account\Core\Domain\Models;

use DateTime;

class Email
{
    private string $email;
    private ?Date $verifiedAt;

    public function __construct(string $email, ?Date $verifiedAt = null)
    {
        $this->email = $email;
        $this->verifiedAt = $verifiedAt;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function getVerifiedAt(): ?Date
    {
        return $this->verifiedAt;
    }

    public function verify(): bool
    {
        if ($this->verifiedAt) {
            return false;
        }

        $this->verifiedAt = new Date(new DateTime());

        return true;
    }

    public function isVerified(): bool
    {
        return (bool) $this->verifiedAt;
    }

    public function isValid(): bool
    {
        return (bool) filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
}