<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Services;

use A7Pro\Marketplace\Toko\Core\Domain\Services\ProfilePicService as ServicesProfilePicService;
use Phalcon\Config;
use Phalcon\Http\Request\File;
use Ramsey\Uuid\Nonstandard\Uuid;

class ProfilePicService implements ServicesProfilePicService
{
    private string $rootDir;
    private string $photoRootPath;

    public function __construct(Config $config)
    {
        $this->rootDir = $config->path('app.storage_path') . "/profile_pics";
        $this->photoRootPath = $config->path('app.photo_root_path') . "/profile_pics";
    }

    public function store(File $photo): string
    {
        try {
            if (!file_exists($this->rootDir))
                mkdir($this->rootDir, 0777, true);

            $filename = Uuid::uuid4()->toString() . "." . $photo->getExtension();
            $storeSuccess = true;
            if (!$photo->moveTo(
                $this->rootDir . "/" . $filename
            ))
                $storeSuccess = false;

            if (!$storeSuccess)
                unlink($this->rootDir . "/" . $filename);
        } catch (\Throwable $th) {
            return false;
        }

        return $filename;
    }

    public function deleteByPhotoId(string $photoId): bool
    {
        return true;
    }

    public function transformPath($photo)
    {
        if (is_null($photo))
            return null;
        else
            return $this->photoRootPath . "/" . $photo;

        return $photo;
    }
}
