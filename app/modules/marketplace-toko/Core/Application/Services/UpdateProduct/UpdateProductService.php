<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProduct;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Product;
use A7Pro\Marketplace\Toko\Core\Domain\Models\ProductId;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductPhotosRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class UpdateProductService
{
    private ProductRepository $productRepository;
    private ProductPhotosService $productPhotosService;
    private ProductPhotosRepository $productPhotosRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductPhotosService $productPhotosService,
        ProductPhotosRepository $productPhotosRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productPhotosService = $productPhotosService;
        $this->productPhotosRepository = $productPhotosRepository;
    }

    public function execute(UpdateProductRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if(!$this->productRepository->isProductExist($request->productId))
            throw new InvalidOperationException('product_not_found');

        $productsSellerId = $this->productRepository->getProductsSellerId([$request->productId]);

        foreach ($productsSellerId as $key => $sellerId)
            if ($sellerId['seller_id'] != $request->sellerId)
                throw new InvalidOperationException('product_not_found');

        // create product
        $product = new Product(
            new ProductId($request->productId),
            $request->categories,
            $request->productName,
            $request->stock,
            $request->price,
            $request->description,
            $request->sellerId,
            $request->weight,
            $request->condition,
            $request->isActive,
            $request->warranty,
            $request->warrantyPeriod,
            $request->brand
        );

        // validate product
        $errors = $product->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        // // store photos to storage
        // $photonames = $this->productPhotosService->store($request->photos, $product->getId()->id());

        // if (!$photonames) {
        //     $this->productRepository->delete([$product->getId()->id()]);

        //     throw new InvalidOperationException("There's something wrong", 500);
        // }

        // return $this->productPhotosRepository->save($photonames, $product->getId()->id());

        return $this->productRepository->update($product);
    }
}
