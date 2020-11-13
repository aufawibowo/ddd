<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Review\ReplyReview;

class ReplyReviewRequest
{
    public ?string $customerId;
    public ?string $productId;
    public ?string $reply_content;
    public ?string $in_reply_to;

    /**
     * ReplyReviewRequest constructor.
     * @param string|null $customerId
     * @param string|null $productId
     * @param string|null $reply_content
     * @param string|null $in_reply_to
     */
    public function __construct(?string $customerId, ?string $productId, ?string $reply_content, ?string $in_reply_to)
    {
        $this->customerId = $customerId;
        $this->productId = $productId;
        $this->reply_content = $reply_content;
        $this->in_reply_to = $in_reply_to;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId)) {
            $errors[] = 'customer_id_must_specified';
        }

        if (!isset($this->productId)) {
            $errors[] = 'product_id_must_specified';
        }

        if (!isset($this->reply_content)) {
            $errors[] = 'reply_content_must_be_specified';
        }

        if (!isset($this->in_reply_to)) {
            $errors[] = 'in_reply_to_must_be_specified';
        }

        return $errors;
    }
}