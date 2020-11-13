<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ReplyReview;

class ReplyReviewRequest
{
    public ?string $sellerId;
    public ?string $reviewId;
    public ?string $replyContent;

    public function __construct(
        ?string $sellerId,
        ?string $reviewId,
        ?string $replyContent
    ) {
        $this->sellerId = $sellerId;
        $this->reviewId = $reviewId;
        $this->replyContent = $replyContent;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->reviewId)) {
            $errors[] = 'review_id_must_be_specified';
        }

        if (!isset($this->replyContent)) {
            $errors[] = 'reply_content_must_be_specified';
        }

        return $errors;
    }
}
