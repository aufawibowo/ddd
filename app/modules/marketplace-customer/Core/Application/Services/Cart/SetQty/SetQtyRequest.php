<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\SetQty;

class SetQtyRequest
{
    public ?string $productId;
    public ?string $customerId;
    public ?string $qty;
    public ?string $cartId;

    /**
     * SetQtyRequest constructor.
     * @param string|null $productId
     * @param string|null $customerId
     * @param string|null $qty
     * @param string|null $cartId
     */
    public function __construct(?string $productId, ?string $customerId, ?string $qty, ?string $cartId)
    {
        $this->productId = $productId;
        $this->customerId = $customerId;
        $this->qty = $qty;
        $this->cartId = $cartId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId))
            $errors[] = 'customer_id_must_be_specified';

        if (!isset($this->cartId))
            $errors[] = 'cart_id_must_be_specified';

        if (!isset($this->qty))
            $errors[] = 'qty_must_be_specified';

        if((int)$this->qty < 1)
            $errors[] = 'qty_must_be_larger_than_or_at_least_1';

        return $errors;
    }
}