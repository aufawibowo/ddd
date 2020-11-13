<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

use A7Pro\Marketplace\Customer\Core\Domain\Models\ShippingProfile;

interface ProfileRepository
{
    public function getProfile(string $customerId);
    public function addAddress(ShippingProfile $address);
    public function getAddress(string $customerId);
    public function getAddressById(string $addressId): ?string;
}