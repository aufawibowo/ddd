<?php

namespace A7Pro\Account\Core\Application\Services\TechnicianLogin;

use A7Pro\Account\Core\Domain\Models\User;

class TechnicianLoginDto
{
    public string $token;
    public string $token_type;
    public object $user;

    public function __construct(string $token, string $token_type, User $technician)
    {
        $this->token = $token;
        $this->token_type = $token_type;
        $this->user = $this->transformTechnician($technician);
    }

    private function transformTechnician(User $technician)
    {
        $obj = new \stdClass();
        $obj->id = $technician->getId()->id();
        $obj->name = $technician->getName();
        $obj->email = $technician->getEmail() ? $technician->getEmail()->email() : null;
        $obj->phone = $technician->getPhone() ? $technician->getPhone()->phone() : null;
        $obj->profile_pict = $technician->getProfilePict();
        $obj->roles = $technician->getRoles();

        return $obj;
    }
}