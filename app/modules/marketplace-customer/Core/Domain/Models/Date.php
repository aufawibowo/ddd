<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;

use DateTime;

class Date
{
    private ?DateTime $datetime = null;

    /**
     * Date constructor.
     * @param DateTime|null $datetime
     */
    public function __construct(?DateTime $datetime)
    {
        $this->datetime = $datetime ?:new DateTime();
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
        return $this->datetime->format(DateTime::ISO8601);
    }

    /**
     * @return string
     */
    public function toDateTimeString(): string
    {
        return $this->datetime->format("Y-m-d H:i:s");
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