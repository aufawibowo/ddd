<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Review;

interface ReviewRepository
{
    public function getReviewById(string $reviewId, string $sellerId): ?array;
    public function getReviewsList(string $sellerId, int $page, int $limit, array $filters): array;
    public function replyReview(Review $replyReview): bool;
}
