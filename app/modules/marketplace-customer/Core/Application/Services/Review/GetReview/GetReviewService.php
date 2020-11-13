<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Review\GetReview;


use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ProductId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ReviewRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Services\ReviewPhotosService;

class GetReviewService
{
    private ReviewRepository $reviewRepository;
    private ReviewPhotosService $reviewPhotosService;

    /**
     * GetReviewService constructor.
     * @param ReviewRepository $reviewRepository
     * @param ReviewPhotosService $reviewPhotosService
     */
    public function __construct(ReviewRepository $reviewRepository, ReviewPhotosService $reviewPhotosService)
    {
        $this->reviewRepository = $reviewRepository;
        $this->reviewPhotosService = $reviewPhotosService;
    }

    public function execute(GetReviewRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $reviews = $this->reviewRepository->get(new ProductId($request->productId));

        foreach ($reviews as $key => $value) {
            $reviews[$key]['photos'] = $this->reviewPhotosService->transformPath($value['photos'], $value['review_id']);
        }

        return $reviews;
    }
}