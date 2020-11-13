<?php

namespace A7Pro\Account\Core\Application\Services\UnverifiedTechnicianList;

use A7Pro\Account\Core\Domain\Models\User;

class UnverifiedTechnicianListDto
{
    public array $data;

    public function __construct(array $users)
    {
        $this->data = $this->transformUserList($users);
    }

    private function transformUserList(array $users): array
    {
        $data = [];

        foreach ($users as $user) {
            $data[] = $this->transformUser($user);
        }

        return $data;
    }

    private function transformUser(User $user): object
    {
        $address = $user->getTechnicianAttributes()->getAddress();

        $obj = new \stdClass();
        $obj->id = $user->getId()->id();
        $obj->apitu_id = $user->getTechnicianAttributes()->getApituId();
        $obj->name = $user->getName();
        $obj->email = $user->getEmail() ? $user->getEmail()->email() : null;
        $obj->phone = $user->getPhone() ? $user->getPhone()->phone() : null;
        $obj->address = $address->getAddress();
        $obj->area = $address->getArea();
        $obj->city = $address->getCity();
        $obj->zip_code = $address->getZipCode()->zipCode();
        $obj->latitude = $address->getCoordinate()->getLatitude();
        $obj->longitude = $address->getCoordinate()->getLongitude();
        $obj->dpc = $user->getTechnicianAttributes()->getDpc()->getName();
        $obj->dpd = $user->getTechnicianAttributes()->getDpc()->getDpd()->getName();

        return $obj;
    }
}