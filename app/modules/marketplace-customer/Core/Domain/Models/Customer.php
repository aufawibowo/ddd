<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class Customer
{
    private CustomerId $customerId;
    private string $name;
    private Email $email;
    private string $phoneNumber;
    private bool $isVerified;
    private bool $isBanned;
    private Cart $cart;
    private Wallet $wallet;
    private Order $order;

    /**
     * Customer constructor.
     * @param CustomerId $customerId
     * @param string $name
     * @param Email $email
     * @param string $phoneNumber
     * @param bool $isVerified
     * @param bool $isBanned
     * @param Cart $cart
     * @param Wallet $wallet
     * @param Order $order
     */
    public function __construct(CustomerId $customerId, string $name, Email $email, string $phoneNumber, bool $isVerified, bool $isBanned, Cart $cart, Wallet $wallet, Order $order)
    {
        $this->customerId = $customerId;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->isVerified = $isVerified;
        $this->isBanned = $isBanned;
        $this->cart = $cart;
        $this->wallet = $wallet;
        $this->order = $order;
    }


}