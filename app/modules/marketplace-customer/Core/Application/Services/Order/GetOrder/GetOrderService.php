<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Order\GetOrder;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\OrderId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\OrderRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Services\ProductPhotosService;

class GetOrderService
{
    private OrderRepository $orderRepository;
    private ProductPhotosService $productPhotoService;

    /**
     * GetOrderService constructor.
     * @param OrderRepository $orderRepository
     * @param ProductPhotosService $productPhotoService
     */
    public function __construct(OrderRepository $orderRepository, ProductPhotosService $productPhotoService)
    {
        $this->orderRepository = $orderRepository;
        $this->productPhotoService = $productPhotoService;
    }

    public function execute(GetOrderRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $order = $this->orderRepository->get(
            new OrderId($request->orderId),
            $request->customerId
        );

        $order[0]['products'][0]['photo_url'] = $this->productPhotoService->transformPath($order[0]['products'][0]['photo_url'], $order[0]['products'][0]['id']);

        return $order[0];
    }
}