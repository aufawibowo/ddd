<?php

namespace A7Pro\Account\Core\Application\Services\HeadOfDppList;

use A7Pro\Account\Core\Domain\Models\User;

class HeadOfDppListDto
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $this->transformUserList($data);
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
        $obj = new \stdClass();
        $obj->id = $user->getId()->id();
        $obj->name = $user->getName();
        $obj->phone = $user->getPhone() ? $user->getPhone()->phone() : null;
        $obj->email = $user->getEmail() ? $user->getEmail()->email() : null;

        $technicianAttributes = $user->getTechnicianAttributes();
        $dpc = $technicianAttributes ? $technicianAttributes->getDpc() : null;
        $dpd = $dpc ? $dpc->getDpd() : null;

        $obj->dpc = $dpc ? $dpc->getName() : null;
        $obj->dpd = $dpd ? $dpd->getName() : null;

        return $obj;
    }
}