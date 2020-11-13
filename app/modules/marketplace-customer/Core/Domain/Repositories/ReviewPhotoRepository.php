<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

interface ReviewPhotoRepository
{
    public function getPhotoById();
    public function save(array $photos, string $reviewId);
    public function delete();
}