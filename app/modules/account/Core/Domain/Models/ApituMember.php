<?php

namespace A7Pro\Account\Core\Domain\Models;

class ApituMember
{
    private string $memberId;
    private string $name;
    private Phone $phone;
    private string $address;
    private string $city;
    private string $dpcCode;

    public function __construct(string $memberId, string $name, Phone $phone, string $address, string $city, string $dpcCode)
    {
        $this->memberId = $memberId;
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->dpcCode = $dpcCode;
    }

    public function getMemberId(): string
    {
        return $this->memberId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getDpcCode(): string
    {
        return $this->dpcCode;
    }
}