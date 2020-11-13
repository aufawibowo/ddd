<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateCouriers;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\CourierRepository;

class UpdateCouriersService
{
    private CourierRepository $courierRepository;

    public function __construct(
        CourierRepository $courierRepository
    ) {
        $this->courierRepository = $courierRepository;
    }

    public function execute(UpdateCouriersRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist
        return $this->courierRepository->updateSellerCouriers($request->couriers, $request->sellerId);
    }
}
