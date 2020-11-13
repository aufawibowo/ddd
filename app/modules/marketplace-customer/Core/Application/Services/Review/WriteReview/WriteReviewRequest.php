<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Review\WriteReview;

class WriteReviewRequest
{
    public ?string $customerId;
    public ?string $productId;
    public ?string $orderId;
    public ?string $review_content;
    public ?int $rating;
    public ?array $photos;

    /**
     * WriteReviewRequest constructor.
     * @param string|null $customerId
     * @param string|null $productId
     * @param string|null $review_content
     * @param int|null $rating
     * @param array|null $photos
     */
    public function __construct(
        ?string $customerId,
        ?string $productId,
        ?string $orderId,
        ?string $review_content,
        ?int $rating,
        ?array $photos
    ){
        $this->customerId = $customerId;
        $this->productId = $productId;
        $this->orderId = $orderId;
        $this->review_content = $review_content;
        $this->rating = $rating;
        $this->photos = $photos;
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

        if (!isset($this->review_content)) {
            $errors[] = 'review_content_must_be_specified';
        }

        if (count($this->photos) > 10){
            $errors[] = 'the_max_num_of_photos_is_10';
        }
        else{
            foreach ($this->photos as $key => $value) {
                $flag = false;

                $extensionsAllowed = ["image/jpg", "image/jpeg", "image/png"];
                if (!in_array($value->getRealType(), $extensionsAllowed)) {
                    $errors[] = 'file_extension_not_allowed';
                    $flag = true;
                }
                if ($value->getSize() > 5000000) {
                    $errors[] = 'file_size_is_too_big';
                    $flag = true;
                }

                if ($flag) break;
            }
        }

        return $errors;
    }
}