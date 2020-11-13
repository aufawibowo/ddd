<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateStorefront;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Storefront;
use A7Pro\Marketplace\Toko\Core\Domain\Models\StorefrontId;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\StorefrontRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\EventPublisher;

class UpdateStorefrontService
{
    private StorefrontRepository $storefrontRepository;
    // private EventPublisher $eventPublisher;

    /**
     * UpdateStorefrontService constructor.
     * @param StorefrontRepository $storefrontRepository
     * @param ShopRepository $shopRepository
     * @param EventPublisher $eventPublisher
     */
    public function __construct(
        StorefrontRepository $storefrontRepository
    ) {
        $this->storefrontRepository = $storefrontRepository;
    }

    public function execute(UpdateStorefrontRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $storefront = $this->storefrontRepository->getStorefrontById($request->storefrontId);

        if (is_null($storefront) || !$storefront->ownedBy($request->sellerId))
            throw new InvalidOperationException('storefront_not_found');

        $storefront = new Storefront(
            new StorefrontId($storefront->getId()->id()),
            $request->sellerId,
            $request->name
        );

        // validate storefront
        $errors = $storefront->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist
        $this->storefrontRepository->update($storefront);
    }
}
