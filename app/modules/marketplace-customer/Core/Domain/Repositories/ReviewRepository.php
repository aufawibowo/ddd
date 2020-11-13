<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

use A7Pro\Marketplace\Customer\Core\Domain\Models\ProductId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Reply;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Review;

interface ReviewRepository
{
    public function getParentReviewById(ProductId $productId);
    public function get(ProductId $productId);
    public function getCustomerList(ProductId $productId);
    public function getTopRatedReview(ProductId $productId);
    public function write(Review $review);
    public function reply(Reply $reply);
    public function rollback(string $reviewId);
    public function isOrderProductReviewed(string $productId, string $orderId): array;
}