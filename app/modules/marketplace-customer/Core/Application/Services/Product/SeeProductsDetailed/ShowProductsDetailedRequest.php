<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Product\SeeProductsDetailed;


class ShowProductsDetailedRequest
{
    public ?string $productId;

    /**
     * ShowProductsDetailedRequest constructor.
     * @param string $productId
     */
    public function __construct(?string $productId)
    {
        $this->productId = $productId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->productId))
        {
            $errors[] = 'product_id_must_be_specified';
        }

        return $errors;
    }
}