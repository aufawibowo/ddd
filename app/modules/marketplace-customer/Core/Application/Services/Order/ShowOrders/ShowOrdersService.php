<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Order\ShowOrders;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\OrderRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Services\ProductPhotosService;

class ShowOrdersService
{
    private OrderRepository $orderRepository;
    private ProductPhotosService $productPhotosService;

    /**
     * ShowOrdersService constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        ProductPhotosService $productPhotosService
    ){
        $this->orderRepository = $orderRepository;
        $this->productPhotosService = $productPhotosService;
    }

    public function execute(ShowOrdersRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $orders = $this->orderRepository->showOrders(
            $request->customerId,
            $request->limit,
            $request->page
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