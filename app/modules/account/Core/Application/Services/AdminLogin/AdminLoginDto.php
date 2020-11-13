<?php

namespace A7Pro\Account\Core\Application\Services\AdminLogin;

use A7Pro\Account\Core\Domain\Models\User;

class AdminLoginDto
{
    public string $token;
    public string $token_type;
    public object $user;

    public function __construct(string $token, string $token_type, User $user)
    {
        $this->token = $token;
        $this->token_type = $token_type;
        $this->user = $this->transformUser($user);
    }

    private function transformUser(User $user)
    {
        $obj = new \stdClass();
        $obj->id = $user->getId()->id();
        $obj->name = $user->getName();
        $obj->email = $user->getEmail() ? $user->getEmail()->email() : null;
        $obj->phone = $user->getPhone() ? $user->getPhone()->phone() : null;
        $obj->profile_pict = $user->getProfilePict();
        $obj->roles = $user->getRoles();

        return $obj;
    }
}