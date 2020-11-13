<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateIsActiveProducts;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;

class UpdateIsActiveProductsService
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function execute(UpdateIsActiveProductsRequest $request)
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

        // persist
        return $this->productRepository->updateIsActiveProducts($request->productsId);
    }
}
