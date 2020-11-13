<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddProductToCart;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Cart;
use A7Pro\Marketplace\Customer\Core\Domain\Models\CartId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductRepository;

class AddProductToCartService
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    /**
     * AddProductToCartService constructor.
     * @param CartRepository $cartRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(AddProductToCartRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        $cart = new Cart(
            new CartId(),
            $request->productId,
            $request->customerId,
            1,
            false,
            null
        );

        $errors = $cart->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        if($this->productRepository->isProductNotExist($request->productId))
            throw new InvalidOperationException('Stok barang habis atau barang tidak tersedia. Tanya penjual untuk lebih lanjut.');

        if($this->cartRepository->isProductInCart($request->productId, $request->customerId) and !is_null($request->cartId))
        {
            return $this->cartRepository->addOne(
                $request->productId,
                $request->customerId,
                $request->cartId
            );
        }
        else{
            return $this->cartRepository->addNew($cart);
        }
    }
}