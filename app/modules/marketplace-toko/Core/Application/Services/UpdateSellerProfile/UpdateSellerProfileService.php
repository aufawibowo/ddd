<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateSellerProfile;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Seller;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\SellerRepository;

class UpdateSellerProfileService
{
    private SellerRepository $sellerRepository;

    public function __construct(
        SellerRepository $sellerRepository
    ) {
        $this->sellerRepository = $sellerRepository;
    }

    public function execute(UpdateSellerProfileRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $sellerProfile = $this->sellerRepository->getSellerProfile($request->sellerId);

        if (!$sellerProfile)
            throw new InvalidOperationException('seller_not_found');

        $seller = new Seller(
            $request->sellerId,
            $request->gender,
            $request->location,
            $request->placeOfBirth,
            $request->dateOfBirth,
            $request->latitude,
            $request->longitude,
            $request->description,
            $request->workingDays,
            $request->openingHours,
            $request->closingHours,
            $request->email,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );

        return $this->sellerRepository->updateSellerProfile($seller);
    }
}
