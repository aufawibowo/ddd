<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

interface CourierRepository
{
    public function getCouriersList(string $sellerId): array;
    public function getCouriersOnCustomerCheckout(string $sellerId): array;
    public function updateSellerCouriers(array $couriers, string $sellerId): bool;
}
