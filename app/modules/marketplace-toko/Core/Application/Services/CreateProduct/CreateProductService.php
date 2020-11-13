<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\CreateProduct;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Product;
use A7Pro\Marketplace\Toko\Core\Domain\Models\ProductId;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductPhotosRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class CreateProductService
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

    public function execute(CreateProductRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // create product
        $product = new Product(
            new ProductId(),
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

        // persist
        $create = $this->productRepository->save($product);

        if (!$create)
            throw new InvalidOperationException("failed_to_store_data", 500);

        // store photos to storage
        if($request->photos){
            $photonames = $this->productPhotosService->store($request->photos, $product->getId()->id());

            if (!$photonames) {
                $this->productRepository->delete([$product->getId()->id()]);

                throw new InvalidOperationException("failed_to_upload", 500);
            }
            else{
                $this->productPhotosRepository->save($photonames, $product->getId()->id());

                return $this->productRepository->updateProductMainPictId(
                    $product->getId()->id(),
                    explode(".", $photonames[0])[0]
                );
            }
        }

        return true;
    }
}
