<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class Cart
{
    private CartId $cartId;
    private string $productId;
    private string $customerId;
    private int $qty;
    private bool $is_checked_out;
    private ?Date $checked_out_at;

    /**
     * Cart constructor.
     * @param CartId $cartId
     * @param ProductId $productId
     * @param CustomerId $customerId
     * @param int $qty
     * @param bool $is_checked_out
     * @param Date $created_at
     * @param Date $updated_at
     * @param Date $deleted_at
     * @param Date $checked_out_at
     */
    public function __construct(
        CartId $cartId,
        string $productId,
        string $customerId,
        int $qty,
        bool $is_checked_out,
        ?Date $checked_out_at
    ){
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->customerId = $customerId;
        $this->qty = $qty;
        $this->is_checked_out = $is_checked_out;
        $this->checked_out_at = $checked_out_at;
    }

    /**
     * @return CartId
     */
    public function getCartId(): CartId
    {
        return $this->cartId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return CustomerId
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @return bool
     */
    public function isIsCheckedOut(): bool
    {
        return $this->is_checked_out;
    }

    /**
     * @return Date
     */
    public function getCheckedOutAt(): ?Date
    {
        return $this->checked_out_at;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->productId))
            $errors[] = 'product_id_must_be_specified';

        if (!isset($this->customerId))
            $errors[] = 'product_id_must_be_specified';

        if (!isset($this->qty))
            $errors[] = 'qty_must_be_specified';

        return $errors;
    }
}