<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfileById;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\SellerRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProfilePicService;

class ShowSellerProfileByIdService
{
    private SellerRepository $sellerRepository;
    private ProfilePicService $profilePicService;

    public function __construct(
        SellerRepository $sellerRepository,
        ProfilePicService $profilePicService
    ) {
        $this->sellerRepository = $sellerRepository;
        $this->profilePicService = $profilePicService;
    }

    public function execute(ShowSellerProfileByIdRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        $sellerProfile = $this->sellerRepository->getSellerProfileById($request->profileId);

        if(!$sellerProfile)
            throw new InvalidOperationException('seller_not_found');

        if($sellerProfile['profile_pict'])
            $sellerProfile['profile_pict'] = $this->profilePicService->transformPath($sellerProfile['profile_pict']);

        return $sellerProfile;
    }
}
