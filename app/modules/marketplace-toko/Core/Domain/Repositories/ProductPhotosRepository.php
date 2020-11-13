<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

interface ProductPhotosRepository
{
    // public function getProductPhotosList(): array;
    public function getPhotoById(string $photoId): ?array;
    public function save(array $filenames, string $productId): bool;
    public function delete(string $photoId): bool;
}
