<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\ShowCart;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Services\ProductPhotosService;

class ShowCartService
{
    private CartRepository $cartRepository;
    private ProductPhotosService $productPhotosService;

    /**
     * ShowCartService constructor.
     * @param CartRepository $cartRepository
     * @param ProductPhotosService $productPhotosService
     */
    public function __construct(CartRepository $cartRepository, ProductPhotosService $productPhotosService)
    {
        $this->cartRepository = $cartRepository;
        $this->productPhotosService = $productPhotosService;
    }

    public function execute(ShowCartRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $result_of_get_seller_id = $this->cartRepository->getSellerId($request->customerId);

        for($enum = 0; $enum<count($result_of_get_seller_id) ;$enum++)
        {
            foreach($result_of_get_seller_id[$enum] as $key => $value['seller_id'])
            {
                $seller_id = $result_of_get_seller_id[$enum]['seller_id'];
                $result[$enum]["seller_id"] = $seller_id;
                $result[$enum]["data"] = $this->cartRepository->get($request->customerId, $result_of_get_seller_id[$enum]['seller_id']);
                $result[$enum]["data"][0]['photo_product'] = $this->productPhotosService->transformPath($result[$enum]["data"][0]['photo_product'], $result[$enum]["data"][0]['product_id']);
            }
        }

        return $result;
    }
}
