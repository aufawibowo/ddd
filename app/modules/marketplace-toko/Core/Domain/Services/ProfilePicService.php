<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Services;

use Phalcon\Http\Request\File;

interface ProfilePicService
{
    public function store(File $photo): string;
    public function deleteByPhotoId(string $photoId): bool;
    public function transformPath(string $filename);
}
