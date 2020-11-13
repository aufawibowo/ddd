<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProducts;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class DeleteProductsService
{
    private ProductRepository $productRepository;
    private ProductPhotosService $productPhotosService;

    public function __construct(
        ProductRepository $productRepository,
        ProductPhotosService $productPhotosService
    ) {
        $this->productRepository = $productRepository;
        $this->productPhotosService = $productPhotosService;
    }

    public function execute(DeleteProductsRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // select products by products_id and check the owner
        $productsSellerId = $this->productRepository->getProductsSellerId($request->productsId);

        foreach ($productsSellerId as $key => $sellerId)
            if ($sellerId['seller_id'] != $request->sellerId)
                throw new InvalidOperationException('product_not_found');

        // // delete product photos
        // $this->productPhotosService->deleteBulk($request->productsId);

        // persist
        return $this->productRepository->delete($request->productsId);
    }
}
