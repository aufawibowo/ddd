<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class Reply
{
    private ReplyId $replyId;
    private ProductId $productId;
    private CustomerId $customerId;
    private string $reply_content;
    private string $inReplyTo;
    private Date $created_at;
    private Date $updated_at;

    /**
     * Reply constructor.
     * @param ReplyId $id
     * @param ProductId $productId
     * @param CustomerId $customerId
     * @param string $reply_content
     * @param string $inReplyTo
     * @param Date $created_at
     * @param Date $updated_at
     */
    public function __construct(ReplyId $replyId, ProductId $productId, CustomerId $customerId, string $reply_content, string $inReplyTo, Date $created_at, Date $updated_at)
    {
        $this->replyId = $replyId;
        $this->productId = $productId;
        $this->customerId = $customerId;
        $this->reply_content = $reply_content;
        $this->inReplyTo = $inReplyTo;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->replyId->id();
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
    public function getCustomerId(): string
    {
        return $this->customerId->id();
    }

    /**
     * @return string
     */
    public function getReplyContent(): string
    {
        return $this->reply_content;
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



}