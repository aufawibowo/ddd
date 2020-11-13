<?php

namespace A7Pro\Account\Core\Domain\Models;

class Customer
{
    private ?string $gender;
    private ?Date $dateOfBirth;

    public function __construct(?string $gender, ?Date $dateOfBirth)
    {
        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getDateOfBirth(): ?Date
    {
        return $this->dateOfBirth;
    }
}
