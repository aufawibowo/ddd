<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowStorefronts;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\StorefrontRepository;

class ShowStorefrontsService
{
    private StorefrontRepository $storefrontRepository;

    public function __construct(
        StorefrontRepository $storefrontRepository
    ) {
        $this->storefrontRepository = $storefrontRepository;
    }

    public function execute(ShowStorefrontsRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist
        return $this->storefrontRepository->getStorefronts($request->sellerId);
    }
}
