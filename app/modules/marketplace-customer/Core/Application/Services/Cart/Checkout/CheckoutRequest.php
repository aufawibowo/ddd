<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\Checkout;

class CheckoutRequest
{
    public ?string $cartId;
    public ?string $customerId;

    /**
     * CheckoutRequest constructor.
     * @param string|null $cartId
     * @param string|null $customerId
     */
    public function __construct(?string $cartId, ?string $customerId)
    {
        $this->cartId = $cartId;
        $this->customerId = $customerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->cartId))
            $errors[] = 'cart_id_must_be_specified';

        if (!isset($this->customerId))
            $errors[] = 'product_id_must_be_specified';

        return $errors;
    }

}