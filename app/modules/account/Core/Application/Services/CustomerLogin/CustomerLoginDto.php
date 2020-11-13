<?php

namespace A7Pro\Account\Core\Application\Services\CustomerLogin;

use A7Pro\Account\Core\Domain\Models\User;

class CustomerLoginDto
{
    public string $token;
    public string $token_type;
    public object $user;

    public function __construct(string $token, string $token_type, User $customer)
    {
        $this->token = $token;
        $this->token_type = $token_type;
        $this->user = $this->transformCustomer($customer);
    }

    private function transformCustomer(User $customer)
    {
        $obj = new \stdClass();
        $obj->id = $customer->getId()->id();
        $obj->name = $customer->getName();
        $obj->email = $customer->getEmail() ? $customer->getEmail()->email() : null;
        $obj->phone = $customer->getPhone() ? $customer->getPhone()->phone() : null;
        $obj->profile_pict = $customer->getProfilePict();
        $obj->roles = $customer->getRoles();

        return $obj;
    }
}