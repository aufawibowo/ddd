<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ReplyReview;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Review;
use A7Pro\Marketplace\Toko\Core\Domain\Models\ReviewId;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ReviewRepository;

class ReplyReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct(
        ReviewRepository $reviewRepository
    ) {
        $this->reviewRepository = $reviewRepository;
    }

    public function execute(ReplyReviewRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        $review = $this->reviewRepository->getReviewById($request->reviewId, $request->sellerId);

        if (is_null($review))
            throw new InvalidOperationException('review_not_found');

        if (!is_null($review['reply']['reply']))
            throw new InvalidOperationException('review_already_replied');

        $replyReview = new Review(
            new ReviewId(),
            $request->sellerId,
            $request->replyContent,
            $request->reviewId
        );

        // validate review
        $errors = $replyReview->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        // persist
        $this->reviewRepository->replyReview($replyReview);
    }
}
