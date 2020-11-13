<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SearchOrders;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\OrderRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class SearchOrdersService
{
    private OrderRepository $orderRepository;
    private ProductPhotosService $productPhotosService;

    public function __construct(
        OrderRepository $orderRepository,
        ProductPhotosService $productPhotosService
    ) {
        $this->orderRepository = $orderRepository;
        $this->productPhotosService = $productPhotosService;
    }

    public function execute(SearchOrdersRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $orders = $this->orderRepository->getOrdersList(
            $request->sellerId,
            $request->page,
            $request->limit,
            null,
            $request->keyword
        );

        foreach ($orders as $key => $value)
            foreach ($value['products'] as $key2 => $value2) {
                $orders[$key]['products'][$key2]['photo_url'] = $this->productPhotosService->transformPath(
                    $value2['photo_url'],
                    $value2['id']
                );
            }

        return $orders;
    }
}
