<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddProductToCart;

class AddProductToCartRequest
{
    public ?string $productId;
    public ?string $customerId;
    public ?string $cartId;

    /**
     * AddProductToCartRequest constructor.
     * @param string|null $productId
     * @param string|null $customerId
     * @param string|null $cartId
     */
    public function __construct(?string $productId, ?string $customerId, ?string $cartId)
    {
        $this->productId = $productId;
        $this->customerId = $customerId;
        $this->cartId = $cartId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->productId))
            $errors[] = 'product_id_must_be_specified';

        if (!isset($this->customerId))
            $errors[] = 'customer_id_must_be_specified';

        return $errors;
    }
}