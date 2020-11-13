<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class Review
{
    private ReviewId $id;
    private ProductId $productId;
    private CustomerId $customerId;
    private string $orderId;
    private string $rating;
    private string $reviewContent;
    private ?string $inReplyTo;
    private Date $created_at;
    private Date $updated_at;

    /**
     * Review constructor.
     * @param ReviewId $id
     * @param ProductId $productId
     * @param CustomerId $customerId
     * @param string $rating
     * @param string $reviewContent
     * @param string|null $inReplyTo
     * @param Date $created_at
     * @param Date $updated_at
     */
    public function __construct(
        ReviewId $id,
        ProductId $productId,
        string $orderId,
        CustomerId $customerId,
        string $rating,
        string $reviewContent,
        ?string $inReplyTo,
        Date $created_at,
        Date $updated_at
    ){
        $this->id = $id;
        $this->productId = $productId;
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->rating = $rating;
        $this->reviewContent = $reviewContent;
        $this->inReplyTo = $inReplyTo;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id->id();
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId->id();
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId->id();
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @return string
     */
    public function getReviewContent(): string
    {
        return $this->reviewContent;
    }

    /**
     * @return string|null
     */
    public function getInReplyTo(): ?string
    {
        return $this->inReplyTo;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at->toDateTimeString();
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at->toDateTimeString();
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

        if (!isset($this->orderId)) {
            $errors[] = 'order_id_must_specified';
        }

        if (!isset($this->rating)) {
            $errors[] = 'rating_must_be_specified';
        }

        if (!isset($this->reviewContent)) {
            $errors[] = 'review_content_must_be_specified';
        }

        return $errors;
    }
}