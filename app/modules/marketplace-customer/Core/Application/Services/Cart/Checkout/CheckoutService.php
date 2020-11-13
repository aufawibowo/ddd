<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\Checkout;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;

class CheckoutService
{
    private CartRepository $cartRepository;

    /**
     * CheckoutService constructor.
     * @param CartRepository $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function execute(CheckoutRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        return $this->cartRepository->checkOut(
            [$request->cartId],
            $request->customerId
        );
    }
}