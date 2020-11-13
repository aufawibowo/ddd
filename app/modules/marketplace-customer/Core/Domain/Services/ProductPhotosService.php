<?php

namespace A7Pro\Marketplace\Customer\Core\Domain\Services;

interface ProductPhotosService
{
    public function store(array $photos, string $productId);
    public function deleteByPhotoId(string $photoId): bool;
    public function deleteBulk(array $productsId): bool;
    public function transformPath($product, string $productId);
}
