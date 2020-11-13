<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\GetHomeData;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\SellerRepository;

class GetHomeDataService
{
    private SellerRepository $sellerRepository;

    public function __construct(
        SellerRepository $sellerRepository
    ) {
        $this->sellerRepository = $sellerRepository;
    }

    public function execute(GetHomeDataRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->sellerRepository->getHomeData($request->sellerId);
    }
}
