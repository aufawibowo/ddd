<?php

namespace A7Pro\Account\Core\Application\Services\GetUserById;

use A7Pro\Account\Core\Domain\Models\Customer;
use A7Pro\Account\Core\Domain\Models\Technician;
use A7Pro\Account\Core\Domain\Models\User;

class GetUserByIdDto
{
    public ?string $id;
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?string $profile_pict;
    public ?array $roles;
    public ?object $technicianAttributes;
    public ?object $customerAttributes;

    public function __construct(User $user)
    {
        $this->id = $user->getId()->id();
        $this->name = $user->getName();
        $this->email = $user->getEmail() ? $user->getEmail()->email() : null;
        $this->phone = $user->getPhone() ? $user->getPhone()->phone() : null;
        $this->profile_pict = $user->getProfilePict();
        $this->roles = $user->getRoles();
        $this->technicianAttributes = $user->getTechnicianAttributes() ?
            $this->transformTechnicianAttributes($user->getTechnicianAttributes()) : null;
        $this->customerAttributes = $user->getCustomerAttributes() ?
            $this->transformCustomerAttributes($user->getCustomerAttributes()) : null;
    }

    public function transformTechnicianAttributes(Technician $technician): object
    {
        $obj = new \stdClass();
        $obj->apitu_id = $technician->getApituId();
        $obj->dpc_id = $technician->getDpc()->getId()->id();
        $obj->address = $technician->getAddress()->getAddress();
        $obj->area = $technician->getAddress()->getArea();
        $obj->city = $technician->getAddress()->getCity();
        $obj->zip_code = $technician->getAddress()->getZipCode()->zipCode();
        $obj->latitude = $technician->getAddress()->getCoordinate()->getLatitude();
        $obj->longitude = $technician->getAddress()->getCoordinate()->getLongitude();
        $obj->receive_order = $technician->isReceiveOrder();

        return $obj;
    }

    public function transformCustomerAttributes(Customer $customer): object
    {
        $obj = new \stdClass();
        $obj->gender = $customer->getGender();
        $obj->date_of_birth = $customer->getDateOfBirth() ? $customer->getDateOfBirth()->toIsoDateString() : null;

        return $obj;
    }
}