<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Review\GetReview;

class GetReviewRequest
{
    public ?string $productId;

    /**
     * GetReviewRequest constructor.
     * @param string|null $productId
     */
    public function __construct(?string $productId)
    {
        $this->productId = $productId;
    }

    public function validate()
    {
        $errors = [];

        if (!isset($this->productId)) {
            $errors[] = 'product_id_must_specified';
        }

        return $errors;
    }


}