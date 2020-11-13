<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class ShippingAddress
{
    private string $address;

    /**
     * ShippingAddress constructor.
     * @param string $address
     */
    public function __construct(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function address(): string
    {
        return $this->address;
    }


}