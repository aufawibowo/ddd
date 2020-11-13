<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;


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

    public function toDateTimeString(): string
    {
        return $this->datetime->format("Y-m-d H:i:s");
    }

    public function toDateOnly(): string
    {
        return $this->datetime->format("Y-m-d");
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
