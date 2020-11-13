<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfile;

class ShowSellerProfileRequest
{
    public ?string $sellerId;

    public function __construct(
        ?string $sellerId
    ) {
        $this->sellerId = $sellerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        return $errors;
    }
}
