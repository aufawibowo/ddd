<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;


interface ProductPhotosRepository
{
    public function getPhotoById(string $photoId): ?array;

}
