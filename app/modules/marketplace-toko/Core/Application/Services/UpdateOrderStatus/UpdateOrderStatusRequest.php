<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateOrderStatus;

class UpdateOrderStatusRequest
{
    public ?string $sellerId;
    public ?string $orderId;
    public ?string $receiptNo;

    public function __construct(
        ?string $sellerId,
        ?string $orderId,
        ?string $receiptNo
    ) {
        $this->sellerId = $sellerId;
        $this->orderId = $orderId;
        $this->receiptNo = $receiptNo;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->orderId)) {
            $errors[] = 'order_id_must_be_specified';
        }

        return $errors;
    }
}
