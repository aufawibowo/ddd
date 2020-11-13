<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SellerLogin;

use A7Pro\Marketplace\Toko\Core\Domain\Models\User;

class SellerLoginDto
{
    public string $token;
    public string $token_type;
    public object $user;

    public function __construct(string $token, string $token_type, User $seller)
    {
        $this->token = $token;
        $this->token_type = $token_type;
        $this->user = $this->transformSeller($seller);
    }

    private function transformSeller(User $seller)
    {
        $obj = new \stdClass();
        $obj->id = $seller->getId();
        $obj->name = $seller->getName();
        $obj->email = $seller->getEmail() ? $seller->getEmail()->email() : null;
        $obj->phone = $seller->getPhone() ? $seller->getPhone()->phone() : null;
        $obj->profile_pict = $seller->getProfilePict();
        $obj->roles = $seller->getRoles();

        return $obj;
    }
}