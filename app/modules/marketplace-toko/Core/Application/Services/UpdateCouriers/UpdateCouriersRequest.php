<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateCouriers;

class UpdateCouriersRequest
{
    public ?string $sellerId;
    public ?array $couriers;

    public function __construct(
        ?string $sellerId,
        ?array $couriers
    ) {
        $this->sellerId = $sellerId;
        $this->couriers = $couriers;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->couriers)) {
            $errors[] = 'couriers_id_must_be_specified';
        }

        return $errors;
    }
}
