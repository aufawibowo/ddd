<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Services;

use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService as ServicesProductPhotosService;
use Phalcon\Config;
use Ramsey\Uuid\Nonstandard\Uuid;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ReviewPhotosService implements ServicesProductPhotosService
{
    private string $rootDir;
    private string $photoRootPath;

    public function __construct(Config $config)
    {
        $this->rootDir = $config->path('app.storage_path') . "/reviews";
        $this->photoRootPath = $config->path('app.photo_root_path') . "/reviews";
    }

    public function store(array $photos, string $reviewId)
    {
        try {
            if (!file_exists($this->rootDir . "/" . $reviewId)){
                mkdir($this->rootDir . "/" . $reviewId, 0777, true);
            }
            $filenames = [];
            $storeSuccess = true;
            foreach ($photos as $key => $value) {
                $filename = Uuid::uuid4()->toString() . "." . $value->getExtension();
                if (!$value->moveTo($this->rootDir . "/" . $reviewId . "/" . $filename)){
                    $storeSuccess = false;
                    break;
                }
                $filenames[] = $filename;
            }

            if (!$storeSuccess){
                foreach ($filenames as $key => $value){
                    unlink($this->rootDir . "/" . $reviewId . "/" . $value);
                }
            }

            return $filenames;
        } catch (\Throwable $th) {
            return false;
        }

    }

    public function deleteByPhotoId(string $photoId): bool
    {
        return true;
    }

    public function deleteBulk(array $reviewsId): bool
    {
        try {
            foreach ($reviewsId as $key => $value) {
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

    public function transformPath($photos, string $reviewId)
    {
        if (is_null($photos))
            return null;
        elseif (is_array($photos))
            foreach ($photos as $key => $value)
                $photos[$key] = $this->photoRootPath . "/" . $reviewId . "/" . $value;
        else
            return $this->photoRootPath . "/" . $reviewId . "/" . $photos;

        return $photos;
    }
}
