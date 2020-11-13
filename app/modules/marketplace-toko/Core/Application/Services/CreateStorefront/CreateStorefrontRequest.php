<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\CreateStorefront;

class CreateStorefrontRequest
{
    public ?string $sellerId;
    public ?string $name;

    public function __construct(
        ?string $sellerId,
        ?string $name
    ) {
        $this->name = $name;
        $this->sellerId = $sellerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->name)) {
            $errors[] = 'name_must_be_specified';
        }

        return $errors;
    }
}
