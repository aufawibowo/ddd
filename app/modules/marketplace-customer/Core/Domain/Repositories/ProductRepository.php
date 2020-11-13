<?php

namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

interface ProductRepository
{
    public function search(
        string $keyword,
        ?int $page,
        ?int $limit,
        ?string $order,
        ?string $sortKey,
        ?string $minimalPrice,
        ?string $maximalPrice,
        ?string $productLocation): array;

    public function getProductById(string $productId): ?array;
    public function getProductReviewById(string $productId): array;
    public function getTokoById(string $productId): array;
    public function isProductNotExist(?string $productId): bool;
}