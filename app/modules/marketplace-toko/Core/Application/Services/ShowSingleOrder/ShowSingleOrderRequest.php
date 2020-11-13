<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleOrder;

class ShowSingleOrderRequest
{
    public ?string $sellerId;
    public ?string $orderId;

    public function __construct(
        ?string $sellerId,
        ?string $orderId
    ) {
        $this->sellerId = $sellerId;
        $this->orderId = $orderId;
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
