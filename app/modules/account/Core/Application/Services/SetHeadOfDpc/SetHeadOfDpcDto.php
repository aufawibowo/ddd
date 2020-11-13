<?php

namespace A7Pro\Account\Core\Application\Services\SetHeadOfDpc;

use A7Pro\Account\Core\Domain\Models\User;

class SetHeadOfDpcDto
{
    public ?string $id;
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?string $profile_pict;
    public ?array $roles;

    public function __construct(User $user)
    {
        $this->id = $user->getId()->id();
        $this->name = $user->getName();
        $this->email = $user->getEmail() ? $user->getEmail()->email() : null;
        $this->phone = $user->getPhone() ? $user->getPhone()->phone() : null;
        $this->profile_pict = $user->getProfilePict();
        $this->roles = $user->getRoles();
    }
}