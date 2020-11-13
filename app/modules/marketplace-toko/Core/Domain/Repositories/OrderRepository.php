<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Order;

interface OrderRepository
{
    public function getOrdersList(string $sellerId, int $page, int $limit, ?int $status, ?string $keyword): array;
    public function getOrderById(string $productId): ?Order;
    public function updateStatusOrder(Order $order, string $receptNo = ""): bool;
}
