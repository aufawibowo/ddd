<?php

namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Product;
use PDO;

class SqlProductRepository implements ProductRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function search(string $keyword, ?int $page, ?int $limit, ?string $order, ?string $sortKey,
       ?string $minimalPrice,
       ?string $maximalPrice,
       ?string $productLocation): array
    {
        $sql = "select distinct
                    p.id, p.name, p.description, p.price, pp.photo_url, s.location, p.brand,
                    avg(rating) as rating_avg, count(rating) as rating_count, p.stock as product_stock
                from
                    products p
                    left join product_photos pp on p.product_pict = pp.id
                    inner join sellers s on p.seller_id = s.user_id
                    left join reviews r on p.id = r.product_id
                where
                    p.name like '%".$keyword."%'
                    and p.deleted_at is null";

        if($minimalPrice && $maximalPrice)
            $sql .= " and p.price between ". $minimalPrice ." and ". $maximalPrice;

        if($productLocation)
            $sql .= " and s.location like '%". $productLocation ."%'";

        $sql .= " group by p.id, p.name, p.description, p.price, pp.photo_url, s.location, p.brand";

        if ($order && $sortKey)
            if(in_array($sortKey, ['created_at', 'price', 'rating']))
                if($sortKey == 'rating')
                    $sql .= " order by rating_count " . $order . ", rating_avg " . $order;
                else
                    $sql .= " order by p." . $sortKey . " " . $order;

        if ($limit)
            $sql .= " limit " . ($page - 1) * $limit . ", " . $limit;

        return $this->db->fetchAll($sql, PDO::FETCH_ASSOC);
    }


    public function getProductById(string $productId): ?array
    {
        $sql = "select
                    p.id, p.name, p.description, p.specification, p.seller_id, p.storefront_id, 
                    p.price, p.stock, p.product_pict as main_pict, p.weight, p.min_order, p.condition,
                    p.is_active, s.name as storefront_name, avg(r.rating) as rating_avg,
                    count(rating) as rating_count, p.created_at, p.updated_at
                from
                    products p
                left join
                    storefronts s on s.id = p.storefront_id
                left join
                    reviews r on p.id = r.product_id
                where p.id = :product_id
                    and p.deleted_at is null
                    and s.deleted_at is null
                group by
                    p.id, p.name, p.description, p.specification, p.seller_id, p.storefront_id, 
                    p.price, p.stock, main_pict, p.weight, p.min_order, p.condition,
                    p.is_active, storefront_name, p.created_at, p.updated_at";

        $param = ['product_id' => $productId];
        $product = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        if (!$product)
            return null;

        $product['condition'] = Product::getConditionText($product['condition']);

        $sql = "select
                    pcp.id, name
                from
                    product_categories pc
                join
                    product_category_pivot pcp on pcp.category_id = pc.id
                where
                    pcp.product_id = :product_id
                    and pcp.deleted_at is null
                    and pc.deleted_at is null";

        $product['categories'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        $sql = "select
                    id, photo_url
                from
                    product_photos
                where
                    product_id = :product_id
                    and deleted_at is null";
        $product['photos'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        return $product;
    }

    public function getProductReviewById(string $productId): array
    {
        // TO-DO
        return [];
    }

    public function getTokoById(string $productId): array
    {
        $sql = "select
                    products.name, products.description, products.price, products.min_order, products.condition, products.is_verified, product_photos.photo_url, 
                from
                    products
                    inner join
                        storefronts
                        on products.storefront_id = storefronts.id
                where
                    products.id = :product_id";

        $params = ['product_id' => $productId];

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        return $results;
    }

    public function isProductNotExist(?string $productId): bool
    {
        $sql = "select * from products where id = :id and stock >= 1";

        $params = [
            'id'    => $productId,
        ];

        $product = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if (!$product) {
            return true;
        }
        else{
            return false;
        }
    }
}