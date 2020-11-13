<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Profile\ShowProfile;

use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProfileRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;

class ShowProfileService
{
    private ProfileRepository $profileRepository;

    /**
     * ShowProfileService constructor.
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function execute(ShowProfileRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->profileRepository->getProfile(
            $request->customerId
        );
    }
}