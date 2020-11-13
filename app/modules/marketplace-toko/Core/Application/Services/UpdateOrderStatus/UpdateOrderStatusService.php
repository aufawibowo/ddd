<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateOrderStatus;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Order;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\OrderRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;

class UpdateOrderStatusService
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(UpdateOrderStatusRequest $request)
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

        if ($order->getStatus() === Order::STATUS_ONORDER) {
            // get expiration and validate if at the moment is past by it or not
            if(strtotime($order->getExpiration()->toDateTimeString()) < strtotime("now"))
                throw new InvalidOperationException('order_is_already_expired');

            foreach ($order->getProducts() as $key => $value)
                if ($value['stock'] < $value['quantity'])
                    throw new InvalidOperationException('insufficient_product_stocks');
        }

        return $this->orderRepository->updateStatusOrder(
            $order,
            $request->receiptNo
        );
    }
}
