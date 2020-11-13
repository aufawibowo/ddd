<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use A7Pro\Marketplace\Customer\Core\Domain\Models\ProductId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Reply;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Review;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ReviewRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;
use PDO;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

class SqlReviewRepository extends SqlBaseRepository implements ReviewRepository
{
    private AbstractPdo $db;

    /**
     * SqlReviewRepository constructor.
     * @param AbstractPdo $db
     */
    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getParentReviewById(ProductId $productId)
    {
        $sql = "select
                    r.rating, r.review_content, r.created_at, u.username
                from
                    reviews as r 
                inner join
                    customers c on r.customer_id = c.user_id
                inner join
                    users u on c.user_id = u.id
                where
                    product_id = :product_id
                    and
                    in_reply_to = 'parent'
                ";

        $params = [];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            $this->db->rollback();

            return false;
        }
    }


    public function get(ProductId $productId)
    {
        $sql = "select
                    r.id as review_id,
                    u.name as customer_name,
                    u.id as customer_id, 
                    r.rating, r.review_content as customer_review, 
                    r2.review_content as toko_reply,
                    r.created_at, rp.photo_url as photos
                from
                    reviews r
                left join
                    reviews r2 on r2.in_reply_to = r.id
                inner join
                    users u on u.id = r.customer_id
                inner join
                    review_photos rp on r.id = rp.review_id
                where
                    r.product_id = :product_id
                    and r.product_id is not null
                    and r.deleted_at is null
                ";

        $params = ['product_id' => $productId->id()];

        $results =  $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        $reviews = [];
        foreach ($results as $key => $value)
        {
            $reviews[] = [
                'review_id' => $value['review_id'],
                'rating' => $value['rating'],
                'customer_review' => $value['customer_review'],
                'toko_reply' => $value['toko_reply'],
                'created_at' => $value['created_at'],
                'photos'    => json_decode($value['photos']),
                'product' => [
                    'id' => $productId->id(),
                ],
                'customer' => [
                    'customer_id' => $value['customer_id'],
                    'customer_name' => $value['customer_name'],
                    'profile_pict' => $value['profile_pict']
                ],
            ];
        }

        $data['reviews'] = $reviews;

        return $reviews;
    }

    public function getTopRatedReview(ProductId $productId)
    {
        $sql = "select
                    r.rating, r.review_content, r.in_reply_to, r.created_at, u.username
                from
                    reviews as r 
                inner join
                    customers c on r.customer_id = c.user_id
                inner join
                    users u on c.user_id = u.id
                where
                    product_id = :product_id
                order by 
                    r.rating desc
                ";

        $params = ['product_id' => $productId->id()];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            $this->db->rollback();

            return false;
        }
    }

    public function write(Review $review)
    {
        $sql = "insert into reviews
                    (id, product_id, order_id, customer_id, rating, review_content, in_reply_to, created_at, updated_at)
                values
                    (:id, :product_id, :order_id, :customer_id, :rating, :review_content, :in_reply_to, :created_at, :updated_at)
                ";

        $params = [
            'id'                => $review->getId(),
            'product_id'        => $review->getProductId(),
            'order_id'        => $review->getOrderId(),
            'customer_id'       => $review->getCustomerId(),
            'rating'            => $review->getRating(),
            'review_content'    => $review->getReviewContent(),
            'in_reply_to'       => $review->getInReplyTo(),
            'created_at'        => $review->getCreatedAt(),
            'updated_at'        => $review->getUpdatedAt()
        ];

        $sqlUpdateOrderProduct = "
                update order_products
                set is_rated = 1
                where product_id = :product_id
                and order_id = :order_id
            ";

        $paramsUpdateOrderProduct = [
            'product_id'        => $review->getProductId(),
            'order_id'        => $review->getOrderId(),
        ];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->execute($sqlUpdateOrderProduct, $paramsUpdateOrderProduct);
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function reply(Reply $reply)
    {
        $sql = "insert into reviews
                    (id, product_id, customer_id, rating, review_content, in_reply_to, created_at, updated_at)
                values
                    (:id, :product_id, :customer_id, :rating, :review_content, :in_reply_to, :created_at, :updated_at)
                ";

        $params = [
            'id'                => $reply->getId(),
            'product_id'        => $reply->getProductId(),
            'customer_id'       => $reply->getCustomerId(),
            'rating'            => null,
            'review_content'    => $reply->getReplyContent(),
            'in_reply_to'       => $reply->getInReplyTo(),
            'created_at'        => $reply->getCreatedAt(),
            'updated_at'        => $reply->getUpdatedAt()
        ];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function rollback(string $reviewId)
    {
        $sql = "delete from
                    reviews
                where
                    id = :review_id";

        $params = ['review_id' => $reviewId];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function getCustomerList(ProductId $productId)
    {
        $sql = "select distinct
                    u.name as customer_name,
                    u.id as customer_id
                    from
                        reviews r
                    left join
                        reviews r2 on r2.in_reply_to = r.id
                    inner join
                        users u on u.id = r.customer_id
                    inner join
                        customers c on r.customer_id = c.user_id
                    where
                        r.product_id = :product_id
                        and r.product_id is not null
                        and r.deleted_at is null
                ";

        $params = ['product_id' => $productId->id()];

        return $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);
    }

    public function isOrderProductReviewed(string $productId, string $orderId): array
    {
        $sql = "select
                    op.is_rated, o.status
                from
                    order_products op
                    inner join orders o on op.order_id = o.id
                where
                    product_id = :product_id
                    and order_id = :order_id";
        $params = [
            'product_id' => $productId,
            'order_id' => $orderId
        ];

        $isRated = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);
        if((int) $isRated['is_rated'])
            return [
                'status' => true,
                'message' => 'product_already_reviewed'
            ];
        elseif((int) $isRated['status'] != Order::STATUS_RECEIVED)
            return [
                'status' => true,
                'message' => 'product_cant_be_reviewed'
            ];

        return [
            'status' => false,
            'message' => ''
        ];
    }
}
