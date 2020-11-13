<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SearchOrders;

class SearchOrdersRequest
{
    public ?string $sellerId;
    public ?int $page;
    public ?int $limit;
    public ?string $keyword;

    public function __construct(
        ?string $sellerId,
        ?int $page,
        ?int $limit,
        ?string $keyword
    ) {
        $this->sellerId = $sellerId;
        $this->page = $page ?: 0;
        $this->limit = $limit ?: 0;
        $this->keyword = $keyword;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if (!isset($this->keyword))
            $errors[] = 'keyword_must_be_specified';

        return $errors;
    }
}
