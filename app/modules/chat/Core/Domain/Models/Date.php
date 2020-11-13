<?php

namespace A7Pro\Chat\Core\Domain\Models;

use DateTime;

/**
 * Class Date
 * @package A7Pro\Marketplace\Toko\Core\Domain\Models
 */
class Date
{
    private \DateTime $datetime;

    /**
     * DateTime constructor.
     * @param \DateTime $datetime
     */
    public function __construct(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return string
     */
    public function toIsoDateString(): string
    {
        return $this->datetime->format('y-m-d');
    }

    /**
     * @return string
     */
    public function toIsoDateTimeString(): string
    {
        return $this->datetime->format(\DateTime::ISO8601);
    }

    /**
     * @param Date $datetime
     * @return bool
     */
    public function dateEquals(Date $datetime): bool
    {
        return $this->toIsoDateString() === $datetime->toIsoDateString();
    }
}
