<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleReview;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ReviewRepository;

class ShowSingleReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct(
        ReviewRepository $reviewRepository
    ) {
        $this->reviewRepository = $reviewRepository;
    }

    public function execute(ShowSingleReviewRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        $review = $this->reviewRepository->getReviewById($request->reviewId, $request->sellerId);

        if (is_null($review))
            throw new InvalidOperationException('review_not_found');

        return $review;
    }
}
