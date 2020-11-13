<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Order\OrderReceived;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;
use A7Pro\Marketplace\Customer\Core\Domain\Models\OrderId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\OrderRepository;

class OrderReceivedService
{
    private OrderRepository $orderRepository;

    /**
     * OrderReceivedService constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function execute(OrderReceivedRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if(
            !$this->orderRepository->isOrderExist(
                $request->orderId,
                $request->customerId,
                Order::STATUS_SHIPPING
            )
        )
            throw new InvalidOperationException("order_not_found", 404);

        return $this->orderRepository->setDone(
            new OrderId($request->orderId)
        );
    }
}