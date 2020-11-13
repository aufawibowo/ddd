<?php

namespace A7Pro\Account\Core\Domain\Models;

class Coordinate
{
    private float $latitude;
    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function isValid(): bool
    {
        // latitude must be between -90 and 90
        // longitude must be between -180 and 180
        return $this->latitude > -90
            && $this->latitude < 90
            && $this->longitude > -180
            && $this->longitude < 180;
    }
}