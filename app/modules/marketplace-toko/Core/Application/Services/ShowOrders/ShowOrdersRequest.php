<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowOrders;

class ShowOrdersRequest
{
    public ?string $sellerId;
    public ?int $page;
    public ?int $limit;
    public ?int $status;

    public function __construct(
        ?string $sellerId,
        ?int $page,
        ?int $limit,
        ?int $status
    ) {
        $this->sellerId = $sellerId;
        $this->page = $page ?: 0;
        $this->limit = $limit ?: 0;
        $this->status = $status ?: 0;
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
