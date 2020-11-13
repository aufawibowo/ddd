<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleReview;

class ShowSingleReviewRequest
{
    public ?string $sellerId;
    public ?string $reviewId;

    public function __construct(
        ?string $sellerId,
        ?string $reviewId
    ) {
        $this->sellerId = $sellerId;
        $this->reviewId = $reviewId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if (!isset($this->reviewId))
            $errors[] = 'review_id_must_be_specified';

        return $errors;
    }
}
