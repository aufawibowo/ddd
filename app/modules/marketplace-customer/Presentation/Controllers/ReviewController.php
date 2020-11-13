<?php


namespace A7Pro\Marketplace\Customer\Presentation\Controllers;


use A7Pro\Marketplace\Customer\Core\Application\Services\Review\GetReview\GetReviewRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Review\GetReview\GetReviewService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Review\ReplyReview\ReplyReviewRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Review\ReplyReview\ReplyReviewService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Review\WriteReview\WriteReviewRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Review\WriteReview\WriteReviewService;
use Exception;

class ReviewController extends BaseController
{
    public function getAction()
    {
        $productId = $this->request->get('productId');

        $request = new GetReviewRequest(
            $productId
        );

        $service = new GetReviewService(
            $this->di->get('reviewRepository'),
            $this->di->get('reviewPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function writeAction()
    {
        $customerId = $this->getAuthUserId();
        $orderId = $this->dispatcher->getParam('order_id');
        $product_id = $this->request->get('product_id');
        $review_content = $this->request->get('review_content');
        $rating =  $this->request->get('rating');
        $photos = $this->request->getUploadedFiles();

        $request = new WriteReviewRequest(
            $customerId,
            $product_id,
            $orderId,
            $review_content,
            $rating,
            $photos
        );

        $service = new WriteReviewService(
            $this->di->get('reviewRepository'),
            $this->di->get('reviewPhotoRepository'),
            $this->di->get('reviewPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendOk($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function replyAction()
    {
        $customerId = $this->getAuthUserId();
        $product_id = $this->request->get('product_id');
        $reply_content = $this->request->get('reply_content');
        $in_reply_to =  $this->request->get('in_reply_to');

        $request = new ReplyReviewRequest(
            $customerId,
            $product_id,
            $reply_content,
            $in_reply_to
        );

        $service = new ReplyReviewService(
            $this->di->get('reviewRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

}