<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\ShowCart;

class ShowCartRequest
{
    public ?string $customerId;

    /**
     * ShowCartRequest constructor.
     * @param string|null $customerId
     */
    public function __construct(?string $customerId)
    {
        $this->customerId = $customerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId))
            $errors[] = 'customer_id_must_be_specified';


        return $errors;
    }
}
