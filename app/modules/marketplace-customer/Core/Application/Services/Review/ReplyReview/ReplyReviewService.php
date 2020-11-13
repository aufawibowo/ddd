<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Review\ReplyReview;


use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\CustomerId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Date;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ProductId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Reply;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ReplyId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ReviewRepository;

class ReplyReviewService
{
    private ReviewRepository $reviewRepository;

    /**
     * GetReviewService constructor.
     * @param ReviewRepository $reviewRepository
     */
    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function execute(ReplyReviewRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $reply = new Reply(
            new ReplyId(),
            new ProductId($request->productId),
            new CustomerId($request->customerId),
            $request->reply_content,
            $request->in_reply_to,
            new Date(new \DateTime()),
            new Date(new \DateTime())
        );

        return $this->reviewRepository->reply(
            $reply
        );
    }
}