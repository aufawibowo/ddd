<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\DeleteProductFromCart;


use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;

class DeleteProductFromCartService
{
    private CartRepository $cartRepository;

    /**
     * AddProductToCartService constructor.
     * @param CartRepository $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    public function execute(DeleteProductFromCartRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->cartRepository->delete(
            $request->cartId,
            $request->customerId
        );
    }
}