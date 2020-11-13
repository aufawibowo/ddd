<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

use A7Pro\Marketplace\Customer\Core\Domain\Models\Cart;
use A7Pro\Marketplace\Customer\Core\Domain\Models\CartId;

interface CartRepository
{
    public function addNew(Cart $cart): bool;
    public function get(string $customerId, string $seller_id);
    public function getSellerId(string $customerId);
    public function delete(string $cartId, string $customerId);
    public function checkOut(array $cartId, string $customerId);
    public function addCatatanKePenjual(CartId $id, string $catatan);
    public function isProductInCart(string $productId, string $customerId);
    public function set(string $cartId, string $customerId, string $qty);
    public function checkProductStock(array $cartIds): array;
    public function addOne(string $productId, string $customerId, string $cartId);

}