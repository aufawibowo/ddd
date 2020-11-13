<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProductsStorefront;

class UpdateProductsStorefrontRequest
{
    public ?string $storefrontId;
    public ?string $sellerId;
    public ?array $productsId;

    public function __construct(
        ?array $productsId,
        ?string $storefrontId,
        ?string $sellerId
    ) {
        $this->sellerId = $sellerId;
        $this->productsId = $productsId;
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

        if (!isset($this->productsId)) {
            $errors[] = 'products_id_must_be_specified';
        }

        return $errors;
    }
}
