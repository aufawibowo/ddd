<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Product\SearchProduct;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Services\ProductPhotosService;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductRepository;

class SearchProductService
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

    public function execute(SearchProductRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $products = $this->productRepository->search(
            $request->keyword,
            $request->page,
            $request->limit,
            $request->order,
            $request->sortKey,
            $request->minimalPrice,
            $request->maximalPrice,
            $request->productLocation
        );

        if (is_null($products)){
            throw new InvalidOperationException('Produk tidak dapat ditemukan.');
        }

        foreach ($products as $key => $value)
            $products[$key]['photo_url'] = $this->productPhotosService->transformPath(
                $value['photo_url'],
                $value['id']
            );

        return $products;
    }
}