<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleProduct;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class ShowSingleProductService
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

    public function execute(ShowSingleProductRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $productsSellerId = $this->productRepository->getProductsSellerId([$request->productId]);

        foreach ($productsSellerId as $key => $sellerId)
            if ($sellerId['seller_id'] != $request->sellerId)
                throw new InvalidOperationException('product_not_found');

        if (!$this->productRepository->isProductExist($request->productId))
            throw new InvalidOperationException('product_not_found');

        $product = $this->productRepository->getProductById($request->productId);

        $product['photos'] = $this->productPhotosService->transformPath($product['photos'], $request->productId);

        return $product;
    }
}
