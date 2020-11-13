<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\DeleteStorefront;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\StorefrontRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\EventPublisher;

class DeleteStorefrontService
{
    private StorefrontRepository $storefrontRepository;
    // private EventPublisher $eventPublisher;

    /**
     * DeleteStorefrontService constructor.
     * @param StorefrontRepository $storefrontRepository
     * @param ShopRepository $shopRepository
     * @param EventPublisher $eventPublisher
     */
    public function __construct(
        StorefrontRepository $storefrontRepository
    ) {
        $this->storefrontRepository = $storefrontRepository;
    }

    public function execute(DeleteStorefrontRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $storefront = $this->storefrontRepository->getStorefrontById($request->storefrontId);

        if (is_null($storefront) || !$storefront->ownedBy($request->sellerId))
            throw new InvalidOperationException('storefront_not_found');

        // persist
        $this->storefrontRepository->delete($storefront);
    }
}
