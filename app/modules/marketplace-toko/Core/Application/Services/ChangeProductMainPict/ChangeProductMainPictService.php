<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ChangeProductMainPict;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;

class ChangeProductMainPictService
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function execute(ChangeProductMainPictRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $product = $this->productRepository->getProductById($request->productId);

        if (is_null($product) || !$product->ownedBy($request->sellerId))
            throw new InvalidOperationException('product_not_found');

        if (!$product->hasPict($request->pictId))
            throw new InvalidOperationException('pict_not_found');

        // persist
        $this->productRepository->changeProductMainPict($product, $request->pictId);
    }
}
