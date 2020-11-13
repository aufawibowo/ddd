<?php

namespace A7Pro\Account\Core\Domain\Models;

class Phone
{
    private string $phone;
    private ?Date $verifiedAt;

    public function __construct(string $phone, ?Date $verifiedAt = null)
    {
        $this->phone = $phone;
        $this->verifiedAt = $verifiedAt;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function masked(): string
    {
        $len = strlen($this->phone) - 7;

        return substr_replace($this->phone, str_repeat('*', $len), 4, -3);
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

    public function isValid(): bool
    {
        $length = strlen($this->phone);

        if (is_numeric($this->phone) && $length >= 10 && $length <= 13) {
            return true;
        }

        return false;
    }
}