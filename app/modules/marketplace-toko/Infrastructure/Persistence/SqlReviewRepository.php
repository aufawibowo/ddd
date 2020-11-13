<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Review;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ReviewRepository;
use PDO;

class SqlReviewRepository implements ReviewRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getReviewById(string $reviewId, string $sellerId): ?array
    {
        $sql = "select
                    p.id, p.rating, p.review_content, p.created_at,
                    c.review_content as reply, c.created_at as reply_created_at,
                    p.product_id, pr.name, pr.product_pict,
                    p.customer_id, u.name as user_name, u.profile_pict
                from
                    reviews p
                left join
                    reviews c on p.id = c.in_reply_to
                inner join
                    products pr on pr.id = p.product_id
                inner join
                    users u on u.id = p.customer_id
                where 
                    p.deleted_at is null
                    and c.deleted_at is null
                    and pr.deleted_at is null
                    and p.customer_id is not null
                    and pr.seller_id = :seller_id
                    and p.id = :id
                limit 1";
        $param = [
            'id' => $reviewId,
            'seller_id' => $sellerId
        ];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);
        if (!$result)
            return null;

        return [
            'id' => $result['id'],
            'rating' => $result['rating'],
            'review' => $result['review_content'],
            'created_at' => $result['created_at'],
            'product' => [
                'id' => $result['product_id'],
                'name' => $result['name'],
                'product_pict' => $result['product_pict']
            ],
            'customer' => [
                'id' => $result['customer_id'],
                'name' => $result['user_name'],
                'profile_pict' => $result['profile_pict']
            ],
            'reply' => [
                'reply' => $result['reply'],
                'created_at' => $result['reply_created_at']
            ]
        ];;
    }

    public function getReviewsList(string $sellerId, int $page, int $limit, array $filters): array
    {
        /* c.review_content as reply, c.created_at as reply_created_at,
        */
        $sql = "select
                    p.id, p.rating, p.review_content, p.created_at,
                    p.product_id, pr.name, pp.photo_url as product_pict,
                    p.customer_id, u.name as user_name, u.profile_pict,
                    c.review_content as reply, rp.photo_url
                from
                    reviews p
                left join
                    reviews c on p.id = c.in_reply_to
                left join
                    review_photos rp on rp.review_id = p.id
                inner join
                    products pr on pr.id = p.product_id
                inner join
                    users u on u.id = p.customer_id
                left join
                    product_photos pp on pp.id = pr.product_pict
                where 
                    p.deleted_at is null
                    and c.deleted_at is null
                    and pr.deleted_at is null
                    and pr.seller_id = :seller_id
                    and p.customer_id is not null";

        if (isset($filters['rating']))
            $sql .= " and p.rating = " . $filters['rating'];

        if (isset($filters['is_unreplied'])) {
            if ($filters['is_unreplied'])
                $sql .= " and c.id is null";
            else
                $sql .= " and c.id is not null";
        }

        $sql .= " order by p.created_at desc";
        if ($limit)
            $sql .= " limit " . ($page - 1) * $limit . ", " . $limit;


        $param = ['seller_id' => $sellerId];
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        $sql = "select
                    user_id, u.profile_pict, u.name
                from
                    sellers s
                join
                    users u on u.id = s.user_id
                where
                    user_id = :seller_id";
        $data['seller'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        $reviews = [];
        foreach ($results as $key => $value)
            $reviews[] = [
                'id' => $value['id'],
                'rating' => $value['rating'],
                'review' => $value['review_content'],
                'reply' => $value['reply'],
                'photo_url' => json_decode($value['photo_url']),
                'created_at' => $value['created_at'],
                'product' => [
                    'id' => $value['product_id'],
                    'name' => $value['name'],
                    'product_pict' => $value['product_pict']
                ],
                'customer' => [
                    'id' => $value['customer_id'],
                    'name' => $value['user_name'],
                    'profile_pict' => $value['profile_pict']
                ],
                // 'reply' => [
                //     'reply' => $value['reply'],
                //     'created_at' => $value['reply_created_at']
                // ]
            ];

        $data['reviews'] = $reviews;

        return $data;
    }

    public function replyReview(Review $replyReview): bool
    {
        $sql = "insert into reviews
                    (id, review_content, in_reply_to)
                values
                    (:id, :review_content, :in_reply_to)";
        $params = [
            'id' => $replyReview->getId()->id(),
            'review_content' => $replyReview->getReplyText(),
            'in_reply_to' => $replyReview->getInReplyTo()
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->commit();
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }
}
