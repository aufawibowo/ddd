<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\CreateStorefront;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Storefront;
use A7Pro\Marketplace\Toko\Core\Domain\Models\StorefrontId;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\StorefrontRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\EventPublisher;

class CreateStorefrontService
{
    private StorefrontRepository $storefrontRepository;
    // private EventPublisher $eventPublisher;

    /**
     * CreateStorefrontService constructor.
     * @param StorefrontRepository $storefrontRepository
     * @param ShopRepository $shopRepository
     * @param EventPublisher $eventPublisher
     */
    public function __construct(
        StorefrontRepository $storefrontRepository
    ) {
        $this->storefrontRepository = $storefrontRepository;
    }

    public function execute(CreateStorefrontRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $storefront = new Storefront(
            new StorefrontId(),
            $request->sellerId,
            $request->name
        );

        // validate storefront
        $errors = $storefront->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist
        $this->storefrontRepository->save($storefront);
    }
}
