<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\SetQty;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductRepository;

class SetQtyService
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    /**
     * SetQtyService constructor.
     * @param CartRepository $cartRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(SetQtyRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        return $this->cartRepository->set(
            $request->cartId,
            $request->customerId,
            $request->qty);
    }
}