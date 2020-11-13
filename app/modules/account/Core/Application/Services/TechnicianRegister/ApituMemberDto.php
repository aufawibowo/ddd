<?php

namespace A7Pro\Account\Core\Application\Services\TechnicianRegister;

use A7Pro\Account\Core\Domain\Models\ApituMember;

class ApituMemberDto
{
    public string $apitu_member_id;
    public string $name;
    public string $phone;
    public string $address;
    public string $city;

    public function __construct(ApituMember $apituMember)
    {
        $this->apitu_member_id = $apituMember->getMemberId();
        $this->name = $apituMember->getName();
        $this->phone = $apituMember->getPhone()->phone();
        $this->address = $apituMember->getAddress();
        $this->city = $apituMember->getCity();
    }
}