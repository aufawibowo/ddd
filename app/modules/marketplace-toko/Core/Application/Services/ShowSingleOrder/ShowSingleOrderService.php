<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleOrder;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\OrderRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService;

class ShowSingleOrderService
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

    public function execute(ShowSingleOrderRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $order = $this->orderRepository->getOrderById(
            $request->orderId
        );

        if (is_null($order) || !$order->ownedBy($request->sellerId))
                throw new InvalidOperationException('order_not_found');

        $products = [];
        foreach ($order->getProducts() as $key => $value) {
            $value['photo_url'] = $this->productPhotosService->transformPath($value['photo_url'], $value['id']);
            $products[] = $value;
        }
        $order->setProducts($products);

        return (new ShowSingleOrderDto($order))->order;
    }
}
