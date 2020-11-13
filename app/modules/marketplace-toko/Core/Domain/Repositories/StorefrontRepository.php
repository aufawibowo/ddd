<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Storefront;

interface StorefrontRepository
{
    public function getStorefrontById(string $storefrontId): ?Storefront;
    public function getStorefronts(string $sellerId): array;
    public function save(Storefront $storefront): bool;
    public function delete(Storefront $storefront): bool;
    public function update(Storefront $storefront): bool;
}
