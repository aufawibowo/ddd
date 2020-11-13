<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateStorefront;

class UpdateStorefrontRequest
{
    public ?string $sellerId;
    public ?string $name;
    public ?string $storefrontId;

    public function __construct(
        ?string $name,
        ?string $sellerId,
        ?string $storefrontId
    ) {
        $this->name = $name;
        $this->sellerId = $sellerId;
        $this->storefrontId = $storefrontId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->storefrontId)) {
            $errors[] = 'storefront_id_must_be_specified';
        }

        if (!isset($this->name)) {
            $errors[] = 'name_must_be_specified';
        }

        return $errors;
    }
}
