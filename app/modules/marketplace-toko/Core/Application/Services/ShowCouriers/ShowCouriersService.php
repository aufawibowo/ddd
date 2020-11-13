<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowCouriers;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\CourierRepository;

class ShowCouriersService
{
    private CourierRepository $courierRepository;

    public function __construct(
        CourierRepository $courierRepository
    ) {
        $this->courierRepository = $courierRepository;
    }

    public function execute(ShowCouriersRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->courierRepository->getCouriersList($request->sellerId);
    }
}
