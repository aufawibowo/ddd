<?php

namespace A7Pro\Account\Core\Domain\Models;

use DateTime;

class Date
{
    private DateTime $date;

    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    public function toIsoDateString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function toIsoDateTimeString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function dateEquals(Date $date): bool
    {
        return $this->toIsoDateString() === $date->toIsoDateString();
    }
}