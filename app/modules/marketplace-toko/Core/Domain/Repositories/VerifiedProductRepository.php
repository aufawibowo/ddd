<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\VerifiedProduct;

interface VerifiedProductRepository
{
    public function getVerifiedProductById(string $verifiedProductId): ?VerifiedProduct;
    public function getVerifiedProducts(string $sellerId): array;
}
