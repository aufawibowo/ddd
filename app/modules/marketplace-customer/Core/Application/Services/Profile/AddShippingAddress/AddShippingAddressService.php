<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Profile\AddShippingAddress;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\CustomerId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Date;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ShippingAddress;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ShippingProfile;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ShippingProfileId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProfileRepository;

class AddShippingAddressService
{
    private ProfileRepository $profileRepository;

    /**
     * AddShippingAddressService constructor.
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function execute(AddShippingAddressRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $address = new ShippingProfile(
            new ShippingProfileId(),
            new ShippingAddress($request->address),
            $request->namaPenerima,
            $request->nomorHpPenerima,
            new CustomerId($request->customerId),
            (float)$request->latitude,
            (float)$request->longitude,
            $request->label,
            new Date(new \DateTime()),
            new Date(new \DateTime())
        );

        return $this->profileRepository->addAddress($address);
    }
}