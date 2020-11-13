<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\CreateInvoice;

class CreateInvoiceRequest
{
    public ?string $customerId;
    public ?array $cartIds;
    public ?string $productId;
    public ?string $paymentMethod;
    public ?string $shippingAddress;
    public ?array $courierIds;

    public function __construct(
        ?string $customerId,
        ?array $cartIds,
        ?string $productId,
        ?string $paymentMethod,
        ?string $shippingAddress,
        ?array $courierIds
    ){
        $this->cartIds = $cartIds;
        $this->productId = $productId;
        $this->customerId = $customerId;
        $this->paymentMethod = $paymentMethod;
        $this->shippingAddress = $shippingAddress;
        $this->courierIds = $courierIds;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId))
            $errors[] = 'customer_id_must_be_specified';

        if ((count($this->cartIds) < 1) && (!isset($this->productId)))
            $errors[] = 'cart_id_or_product_id_must_be_specified';

        if (!isset($this->paymentMethod))
            $errors[] = 'payment_method_must_be_specified';

        if (!isset($this->shippingAddress))
            $errors[] = 'shipping_address_must_be_specified';

        if (count($this->courierIds) < 1)
            $errors[] = 'courier_id_must_be_specified';

        return $errors;
    }
}