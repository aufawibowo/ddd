<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

use A7Pro\Marketplace\Customer\Core\Domain\Models\OrderId;

interface OrderRepository
{
    public function showOrders(string $customerId, int $limit, int $page): array;
    public function isOrderExist(string $orderId, string $customerId, ?int $status): bool;
    public function get(OrderId $orderId, string $customerId);
    public function cancelOrder(string $orderId): bool;
    public function setDone(OrderId $orderId);
}