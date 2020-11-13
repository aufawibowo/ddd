<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Seller;

interface SellerRepository
{
    public function getSellerProfile(string $sellerId): ?array;
    public function getSellerProfileById(string $sellerId): ?array;
    public function updateSellerProfile(Seller $seller): bool;
    public function getSellerByEmailOrUsername(string $email, string $username): array;
    public function getHomeData(string $sellerId): array;
    public function save(Seller $seller): bool;
}
