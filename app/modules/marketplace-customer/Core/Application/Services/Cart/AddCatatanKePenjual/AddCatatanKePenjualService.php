<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddCatatanKePenjual;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\CartId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;

class AddCatatanKePenjualService
{
    private CartRepository $cartRepository;

    /**
     * AddCatatanKePenjualService constructor.
     * @param CartRepository $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function execute(AddCatatanKePenjualRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        return $this->cartRepository->addCatatanKePenjual(new CartId($request->cartId), $request->catatan);
    }
}