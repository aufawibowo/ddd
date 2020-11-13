<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Product\SeeProductsDetailed;


use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Services\ProductPhotosService;

class ShowProductsDetailedService
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

    public function execute(ShowProductsDetailedRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $product = $this->productRepository->getProductById($request->productId);

        if (is_null($product))
            throw new InvalidOperationException('Produk tidak dapat ditemukan.');

        $product['photos'] = $this->productPhotosService->transformPath($product['photos'], $request->productId);

        return $product;
    }
}