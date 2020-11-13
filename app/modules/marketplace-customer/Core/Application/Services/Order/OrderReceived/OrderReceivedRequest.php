<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Order\OrderReceived;

class OrderReceivedRequest
{
    public ?string $orderId;
    public ?string $customerId;

    /**
     * OrderReceivedRequest constructor.
     * @param string|null $orderId
     */
    public function __construct(?string $orderId, ?string $customerId)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->orderId)){
            $errors[] = 'order_id_must_be_specified';
        }

        if (!isset($this->customerId)){
            $errors[] = 'customer_id_must_be_specified';
        }

        return $errors;
    }
}