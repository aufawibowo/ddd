<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowProducts;

class ShowProductsRequest
{
    public ?string $sellerId;
    public ?int $page;
    public ?int $limit;
    public ?array $filters;

    public function __construct(
        ?string $sellerId,
        ?int $page,
        ?int $limit,
        ?array $filters
    ) {
        $this->sellerId = $sellerId;
        $this->page = $page ?: 0;
        $this->limit = $limit ?: 0;
        $this->filters = $filters ?: [];
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
