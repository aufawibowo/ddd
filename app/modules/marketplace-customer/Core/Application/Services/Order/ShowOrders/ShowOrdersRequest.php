<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Order\ShowOrders;

class ShowOrdersRequest
{
    public ?string $customerId;
    public ?int $limit;
    public ?int $page;

    /**
     * ShowOrdersRequest constructor.
     * @param string|null $customerId
     * @param int|null $limit
     * @param int|null $page
     */
    public function __construct(?string $customerId, ?int $limit, ?int $page)
    {
        $this->customerId = $customerId;
        $this->page = $page ?: 0;
        $this->limit = $limit ?: 0;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId))
            $errors[] = 'customer_id_must_be_specified';

        return $errors;
    }
}