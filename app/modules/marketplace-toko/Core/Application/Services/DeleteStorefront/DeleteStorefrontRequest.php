<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\DeleteStorefront;

class DeleteStorefrontRequest
{
    public ?string $storefrontId;
    public ?string $sellerId;

    public function __construct(
        ?string $storefrontId,
        ?string $sellerId
    ) {
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

        return $errors;
    }
}
