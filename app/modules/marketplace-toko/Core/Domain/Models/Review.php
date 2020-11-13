<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;

class Review
{
    private ReviewId $reviewId;
    private string $sellerId;
    private string $replyText;
    private string $inReplyTo;

    public function __construct(
        ReviewId $reviewId,
        string $sellerId,
        string $replyText,
        string $inReplyTo
    ) {
        $this->reviewId = $reviewId;
        $this->replyText = $replyText;
        $this->sellerId = $sellerId;
        $this->inReplyTo = $inReplyTo;
    }

    /**
     * @return ReviewId
     */
    public function getId(): ReviewId
    {
        return $this->reviewId;
    }

    /**
     * @return string
     */
    public function getSellerId(): string
    {
        return $this->sellerId;
    }

    /**
     * @return string
     */
    public function getInReplyTo(): string
    {
        return $this->inReplyTo;
    }

    /**
     * @return string
     */
    public function getReplyText(): string
    {
        return $this->replyText;
    }

    /**
     * @return bool
     */
    public function ownedBy($sellerId): bool
    {
        return $this->sellerId == $sellerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->reviewId))
            $errors[] = 'id_must_be_specified';

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if (!isset($this->replyText))
            $errors[] = 'reply_content_must_be_specified';

        if (!isset($this->inReplyTo))
            $errors[] = 'review_id_must_be_specified';

        return $errors;
    }
}
