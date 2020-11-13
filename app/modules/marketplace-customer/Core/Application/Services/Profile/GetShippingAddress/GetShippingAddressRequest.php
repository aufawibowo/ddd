<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Profile\GetShippingAddress;


class GetShippingAddressRequest
{
    public ?string $customerId;

    /**
     * GetShippingAddressRequest constructor.
     * @param string|null $customerId
     */
    public function __construct(?string $customerId)
    {
        $this->customerId = $customerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId)) {
            $errors[] = 'customerId_must_specified';
        }

        return $errors;
    }
}