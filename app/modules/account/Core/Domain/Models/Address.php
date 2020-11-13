<?php

namespace A7Pro\Account\Core\Domain\Models;

class Address
{
    private string $address;
    private string $area;
    private string $city;
    private ZipCode $zipCode;
    private Coordinate $coordinate;

    public function __construct(string $address, string $area, string $city, ZipCode $zipCode, Coordinate $coordinate)
    {
        $this->address = $address;
        $this->area = $area;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->coordinate = $coordinate;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): ZipCode
    {
        return $this->zipCode;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}