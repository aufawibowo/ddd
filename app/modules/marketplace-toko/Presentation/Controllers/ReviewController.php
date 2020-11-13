<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleReview\ShowSingleReviewRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleReview\ShowSingleReviewService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowReviews\ShowReviewsRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowReviews\ShowReviewsService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ReplyReview\ReplyReviewRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ReplyReview\ReplyReviewService;

class ReviewController extends BaseController
{
    public function showSingleReviewAction()
    {
        $sellerId = $this->getAuthUserId();
        $reviewId = $this->dispatcher->getParam('review_id');

        $request = new ShowSingleReviewRequest($sellerId, $reviewId);

        $service = new ShowSingleReviewService(
            $this->di->get('reviewRepository')
        );

        try {
            $review = $service->execute($request);

            $this->sendData($review);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function showReviewsAction()
    {
        $sellerId = $this->getAuthUserId();
        $limit = $this->request->get('limit');
        $page = $this->request->get('page');
        $filters = $this->request->get('filters');

        $request = new ShowReviewsRequest($sellerId, $page, $limit, $filters);

        $service = new ShowReviewsService(
            $this->di->get('reviewRepository'),
            $this->di->get('reviewPhotosService'),
            $this->di->get('productPhotosService')
        );

        try {
            $reviews = $service->execute($request);

            $this->sendData($reviews);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function replyReviewAction()
    {
        $sellerId = $this->getAuthUserId();
        $reviewId = $this->dispatcher->getParam('review_id');
        $replyContent = $this->request->get('reply_content');

        $request = new ReplyReviewRequest($sellerId, $reviewId, $replyContent);

        $service = new ReplyReviewService(
            $this->di->get('reviewRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
