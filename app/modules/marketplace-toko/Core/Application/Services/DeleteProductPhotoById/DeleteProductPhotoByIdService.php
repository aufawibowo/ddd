<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProductPhotoById;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductPhotosRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;

class DeleteProductPhotoByIdService
{
    private ProductPhotosRepository $productPhotoRepository;
    private ProductRepository $productRepository;

    public function __construct(
        ProductPhotosRepository $productPhotoRepository,
        ProductRepository $productRepository
    ) {
        $this->productPhotoRepository = $productPhotoRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(DeleteProductPhotoByIdRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $productPhoto = $this->productPhotoRepository->getPhotoById($request->photoId);

        if (!$productPhoto)
            throw new InvalidOperationException('photo_not_found');

        $product = $this->productRepository->getProductById($productPhoto['product_id']);

        if ($product['seller_id'] != $request->sellerId)
            throw new InvalidOperationException('photo_not_found');

        // persist
        $this->productPhotoRepository->delete($request->photoId);
    }
}
