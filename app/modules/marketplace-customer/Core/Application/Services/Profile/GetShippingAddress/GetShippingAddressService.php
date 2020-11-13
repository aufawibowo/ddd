<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Profile\GetShippingAddress;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProfileRepository;

class GetShippingAddressService
{
    private ProfileRepository $profileRepository;

    /**
     * GetShippingAddressService constructor.
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function execute(GetShippingAddressRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->profileRepository->getAddress(
            $request->customerId
        );
    }
}