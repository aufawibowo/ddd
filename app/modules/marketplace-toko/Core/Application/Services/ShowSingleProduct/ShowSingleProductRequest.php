<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleProduct;

class ShowSingleProductRequest
{
    public ?string $sellerId;
    public ?string $productId;

    public function __construct(
        ?string $sellerId,
        ?string $productId
    ) {
        $this->sellerId = $sellerId;
        $this->productId = $productId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if (!isset($this->productId))
            $errors[] = 'product_id_must_be_specified';

        return $errors;
    }
}
