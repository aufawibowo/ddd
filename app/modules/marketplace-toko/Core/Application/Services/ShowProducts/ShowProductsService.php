<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowProducts;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class ShowProductsService
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

    public function execute(ShowProductsRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $products =  $this->productRepository->getProductsList(
            $request->sellerId,
            $request->page,
            $request->limit,
            $request->filters
        );

        foreach ($products as $key => $value)
            $products[$key]['photo_url'] = $this->productPhotosService->transformPath(
                $value['photo_url'],
                $value['id']
            );

        return $products;
    }
}
