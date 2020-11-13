<?php

namespace A7Pro\Wallet\Core\Domain\Models;

class Date
{
    private \DateTime $date;

    public function __construct(\DateTime $date = null)
    {
        $this->date = $date ?: new \DateTime();
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