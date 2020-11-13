<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Product;

interface ProductRepository
{
    public function getProductsList(string $sellerId, int $page, int $limit, array $filters = []): array;
    public function isProductExist(string $productId): bool;
    public function getProductById(string $productId): ?array;
    public function getProductsSellerId(array $productsId): ?array;
    public function changeProductMainPict(Product $product, string $pictId): bool;
    public function updateProductMainPictId(string $productId, string $pictId): bool;
    public function updateProductsStorefront(array $productsId, string $storefrontId): bool;
    public function updateIsActiveProducts(array $productsId): bool;
    public function update(Product $product): bool;
    public function updateStockBulk(array $products): bool;
    public function save(Product $product): bool;
    public function delete(array $productsId): bool;
}
