<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProducts;

class DeleteProductsRequest
{
    public ?string $sellerId;
    public ?array $productsId;

    public function __construct(
        ?string $sellerId,
        ?array $productsId
    ) {
        $this->sellerId = $sellerId;
        $this->productsId = $productsId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->productsId)) {
            $errors[] = 'products_id_must_be_specified';
        }

        return $errors;
    }
}
