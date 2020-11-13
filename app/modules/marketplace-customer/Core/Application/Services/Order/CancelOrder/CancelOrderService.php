<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Order\CancelOrder;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\OrderRepository;

class CancelOrderService
{
    private OrderRepository $orderRepository;

    /**
     * CancelOrderService constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function execute(CancelOrderRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        if(
            !$this->orderRepository->isOrderExist(
                $request->orderId,
                $request->customerId,
                Order::STATUS_ONORDER
            )
        )
            throw new InvalidOperationException("order_not_found", 404);

        return $this->orderRepository->cancelOrder(
            $request->orderId
        );
    }
}