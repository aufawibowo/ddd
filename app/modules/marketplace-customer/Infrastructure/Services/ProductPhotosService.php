<?php

namespace A7Pro\Marketplace\Customer\Infrastructure\Services;

use A7Pro\Marketplace\Customer\Core\Domain\Services\ProductPhotosService as ServicesProductPhotosService;
use Phalcon\Config;
use Ramsey\Uuid\Nonstandard\Uuid;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ProductPhotosService implements ServicesProductPhotosService
{
    private string $rootDir;
    private string $photoRootPath;

    public function __construct(Config $config)
    {
        $this->rootDir = $config->path('app.storage_path') . "/products";
        $this->photoRootPath = $config->path('app.photo_root_path') . "/products";
    }

    public function store(array $photos, string $productId)
    {
        try {
            if (!file_exists($this->rootDir . "/" . $productId))
                mkdir($this->rootDir . "/" . $productId, 0777, true);

            $filenames = [];
            $storeSuccess = true;
            foreach ($photos as $key => $value) {
                $filename = Uuid::uuid4()->toString() . "." . $value->getExtension();
                if (!$value->moveTo(
                    $this->rootDir . "/" . $productId . "/" . $filename
                )) {
                    $storeSuccess = false;
                    break;
                }

                $filenames[] = $filename;
            }

            if (!$storeSuccess)
                foreach ($filenames as $key => $value)
                    unlink($this->rootDir . "/" . $productId . "/" . $value);
        } catch (\Throwable $th) {
            return false;
        }

        return $filenames;
    }

    public function deleteByPhotoId(string $photoId): bool
    {
        return true;
    }

    public function deleteBulk(array $productsId): bool
    {
        try {
            foreach ($productsId as $key => $value) {
                $dir = $this->rootDir . DIRECTORY_SEPARATOR . $value;
                $iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator(
                    $iterator,
                    RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($files as $file)
                    if ($file->isDir())
                        rmdir($file->getRealPath());
                    else
                        unlink($file->getRealPath());

                rmdir($dir);
            }
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    public function transformPath($photos, string $productId)
    {
        if (is_null($photos))
            return null;
        elseif (is_array($photos))
            foreach ($photos as $key => $value)
                $photos[$key]['photo_url'] =
                    $this->photoRootPath . "/" . $productId . "/" . $value['photo_url'];
        else
            return $this->photoRootPath . "/" . $productId . "/" . $photos;

        return $photos;
    }
}
