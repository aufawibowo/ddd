<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowReviews;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ReviewRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProductPhotosService as ReviewPhotosService;

class ShowReviewsService
{
    private ReviewRepository $reviewRepository;
    private ReviewPhotosService $reviewPhotosService;
    private ReviewPhotosService $productPhotosService;

    public function __construct(
        ReviewRepository $reviewRepository,
        ReviewPhotosService $reviewPhotosService,
        ReviewPhotosService $productPhotosService
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->reviewPhotosService = $reviewPhotosService;
        $this->productPhotosService = $productPhotosService;
    }

    public function execute(ShowReviewsRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $reviews =  $this->reviewRepository->getReviewsList(
            $request->sellerId,
            $request->page,
            $request->limit,
            $request->filters
        );

        foreach ($reviews['reviews'] as $key => $value) {
            $reviews['reviews'][$key]['photo_url'] =
                $this->reviewPhotosService->transformPath($value['photo_url'], $value['id']);
                
            $reviews['reviews'][$key]['product']['product_pict'] =
                $this->productPhotosService->transformPath(
                    $value['product']['product_pict'], $value['product']['id']
                );
        }

        return $reviews;
    }
}
