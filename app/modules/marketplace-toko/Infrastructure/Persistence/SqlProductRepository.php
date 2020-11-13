<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Date;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Product;
use PDO;

class SqlProductRepository implements ProductRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function isProductExist(string $productId): bool
    {
        $sql = "select 1 from products
                where id = :product_id and deleted_at is null";
        $param = ['product_id' => $productId];

        if($this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param))
            return true;
            
        return false;
    }

    public function getProductsList(
        string $sellerId,
        int $page,
        int $limit,
        array $filters = []
    ): array {
        $sql = "select
                    p.id, p.name, p.price, p.stock, p.condition, p.is_active,
                    pp.photo_url, p.storefront_id, s.name as storefront_name,
                    p.brand, p.verified_id, p.created_at
                from 
                    products p
                left join 
                    product_photos pp on p.product_pict = pp.id
                left join 
                    storefronts s on p.storefront_id = s.id
                where p.deleted_at is null
                    and p.seller_id = :seller_id";

        $params = ['seller_id' => $sellerId];
        if (count($filters)) {
            if (isset($filters['name']))
                $sql .= " and p.name like '%" . $filters['name'] . "%'";

            if (isset($filters['storefronts']))
                $sql .= " and storefront_id in " . $this->whereInBuilder($filters['storefronts']);
        }

        $sql .= " order by p.created_at desc";
        if ($limit)
            $sql .= " limit " . ($page - 1) * $limit . ", " . $limit;
        $products = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        $toUnsetProductsList = [];
        foreach ($products as $key => $value) {
            $sql = "select pc.id, pc.name from
                        product_category_pivot pcp
                    join
                        product_categories pc on pc.id = pcp.category_id
                    where
                        pcp.deleted_at is null
                        and pc.deleted_at is null
                        and product_id = :product_id";

            if (isset($filters['categories']))
                $sql .= " and category_id in " . $this->whereInBuilder($filters['categories']);

            $params = ['product_id' => $value['id']];
            $products[$key]['categories'] =
                $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

            if (!$products[$key]['categories'])
                $toUnsetProductsList[] = $products[$key]['id'];

            $products[$key]['condition'] = Product::getConditionText($value['condition']);
        }

        if (isset($filters['categories']))
            foreach ($toUnsetProductsList as $key => $value)
                foreach ($products as $key2 => $value2) {
                    if ($products[$key2]['id'] == $value) {
                        array_splice($products, $key2, 1);
                        break;
                    }
                }

        return $products;
    }

    public function changeProductMainPict(Product $product, string $pictId): bool
    {
        return true;
    }

    public function getProductById(string $productId): ?array
    {
        $sql = "select
                    p.id, p.name, p.stock, p.price, p.description, p.seller_id,
                    p.storefront_id, s.name as storefront_name, p.condition,
                    p.is_active, product_pict as main_pict,
                    p.warranty, p.warranty_period, p.brand,
                    p.created_at, p.updated_at
                from 
                    products p
                left join 
                    storefronts s on s.id = p.storefront_id
                where p.id = :product_id
                    and p.deleted_at is null
                    and s.deleted_at is null";
        $param = ['product_id' => $productId];
        $product = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        $product['warranty_period'] = Product::getWarrantyPeriodText($product['warranty_period']);

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

    public function update(Product $product): bool
    {
        $sqlUpdate = "update
                    products 
                set 
                    name = :name, price = :price, stock = :stock,
                    weight = :weight,  description = :description,
                    `condition` = :condition, is_active = :is_active,
                    brand = :brand, warranty = :warranty,
                    warranty_period = :warranty_period
                where
                    id = :id";
        $paramsUpdate = [
            'id' => $product->getId()->id(),
            'name' => $product->getProductName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock(),
            'condition' => $product->getCondition(),
            'weight' => $product->getWeight(),
            'is_active' => $product->isActive(),
            'brand' => $product->getBrand(),
            'warranty' => $product->getWarranty(),
            'warranty_period' => $product->getWarrantyPeriod(),
        ];

        $sqlSelectCategories = "select category_id
            from
                product_category_pivot
            where
                deleted_at is null
                and product_id = :product_id";
        $paramSelectCategories = [
            'product_id' => $product->getId()->id()
        ];
        $results = $this->db->fetchAll($sqlSelectCategories, PDO::FETCH_ASSOC, $paramSelectCategories);
        $categories = array_column($results, "category_id");

        $newCategories = [];
        foreach ($product->getCategory() as $key => $value)
            if (!in_array($value, $categories))
                $newCategories[] = $value;

        $sqlCategoryPivot = "insert into product_category_pivot
                    (product_id, category_id)
                values ";

        foreach ($newCategories as $key => $value) {
            $sqlCategoryPivot .= "(:product_id, " . "'" . $value . "')";

            if ($newCategories[$key + 1]) $sqlCategoryPivot .= ",";
        }

        $sqlDeleteUncategorized = "update product_category_pivot
            set 
                deleted_at = :deleted_at
            where 
                product_id = :product_id
                and deleted_at is null
                and category_id not in " . $this->whereInBuilder($product->getCategory());

        $deletedAt = new Date(new \DateTime());
        $paramsDeleteProductCategoryPivot = [
            'deleted_at' => $deletedAt->toDateTimeString(),
            'product_id' => $product->getId()->id()
        ];

        try {
            $this->db->begin();

            if(count($newCategories))
                $this->db->execute($sqlCategoryPivot, $paramSelectCategories);
            $this->db->execute($sqlDeleteUncategorized, $paramsDeleteProductCategoryPivot);
            $this->db->execute($sqlUpdate, $paramsUpdate);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function getProductsSellerId(array $productsId): ?array
    {
        $sql = "select distinct seller_id
                from
                    products p
                where 
                    p.deleted_at is null
                    and id in " . $this->whereInBuilder($productsId);

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        return $results;
    }

    public function updateProductsStorefront(array $productsId, string $storefrontId): bool
    {
        $sql = "select
                    id, storefront_id
                from
                    products
                where
                    deleted_at is null
                    and id in " . $this->whereInBuilder($productsId);
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $storefrontNull = [];
        $storefrontNotNull = [];
        foreach ($results as $key => $value)
            if ($value['storefront_id']) $storefrontNull[] = $value['id'];
            else $storefrontNotNull[] = $value['id'];

        $sqlUpdate1 = "update
                            products
                        set
                            storefront_id = :storefront_id
                        where
                            storefront_id IS NULL
                            and id in " . $this->whereInBuilder($storefrontNotNull);

        $sqlUpdate2 = "update
                            products
                        set
                            storefront_id = NULL
                        where
                            storefront_id = :storefront_id
                            and id in " . $this->whereInBuilder($storefrontNull);

        $param = ['storefront_id' => $storefrontId];

        try {
            $this->db->begin();

            $this->db->execute($sqlUpdate1, $param);

            $this->db->execute($sqlUpdate2, $param);

            $this->db->commit();

            return true;
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function updateIsActiveProducts(array $productsId): bool
    {
        $sql = "select
                    id, is_active
                from
                    products
                where
                    deleted_at is null
                    and id in " . $this->whereInBuilder($productsId);
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $inactiveProducts = [];
        $activeProducts = [];
        foreach ($results as $key => $value)
            if ($value['is_active']) $inactiveProducts[] = $value['id'];
            else $activeProducts[] = $value['id'];

        $sqlUpdate1 = "update
                            products
                        set
                            is_active = 0
                        where
                            is_active = 1
                            and id in " . $this->whereInBuilder($inactiveProducts);

        $sqlUpdate2 = "update
                            products
                        set
                            is_active = 1
                        where
                            is_active = 0
                            and id in " . $this->whereInBuilder($activeProducts);

        try {
            $this->db->begin();

            $this->db->execute($sqlUpdate1);

            $this->db->execute($sqlUpdate2);

            $this->db->commit();

            return true;
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function updateProductMainPictId(string $productId, string $pictId): bool
    {
        $sql = "update products
                set product_pict = :product_pict
                where id = :id";
                
        $params = [
            'product_pict' => $pictId,
            'id' => $productId,
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->commit();

            var_dump('asddas');

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function save(Product $product): bool
    {
        $sql = "insert into products 
                    (id, name, seller_id, price, stock, weight, description, `condition`,
                    is_active, brand, warranty, warranty_period)
                values 
                    (:id, :name, :seller_id, :price, :stock, :weight,
                    :description, :condition, :is_active, :brand, :warranty,
                    :warranty_period)";

        $params = [
            'id' => $product->getId()->id(),
            'name' => $product->getProductName(),
            'description' => $product->getDescription(),
            'seller_id' => $product->getSellerId(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock(),
            'condition' => $product->getCondition(),
            'weight' => $product->getWeight(),
            'is_active' => $product->isActive(),
            'brand' => $product->getBrand(),
            'warranty' => $product->getWarranty(),
            'warranty_period' => $product->getWarrantyPeriod(),
        ];

        $sqlCategoryPivot = "insert into product_category_pivot
                    (product_id, category_id)
                values ";

        $categories = $product->getCategory();
        foreach ($categories as $key => $value) {
            $sqlCategoryPivot .= "(:product_id, " . "'" . $value . "')";

            if ($categories[$key + 1]) $sqlCategoryPivot .= ",";
        }

        $paramCategoryPivot = [
            'product_id' => $product->getId()->id(),
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->execute($sqlCategoryPivot, $paramCategoryPivot);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function delete(array $productsId): bool
    {
        $sqlDeleteCategory = "update
                    product_category_pivot
                set
                    deleted_at = :deleted_at
                where
                    deleted_at IS NULL
                    and product_id in " . $this->whereInBuilder($productsId);

        $sqlDeletePhotos = "update
                    product_photos
                set
                    deleted_at = :deleted_at
                where
                    deleted_at IS NULL
                    and product_id in " . $this->whereInBuilder($productsId);

        $sql = "update
                    products
                set
                    deleted_at = :deleted_at
                where
                    deleted_at IS NULL
                    and id in " . $this->whereInBuilder($productsId);

        $deletedAt = new Date(new \DateTime());
        $params = ['deleted_at' => $deletedAt->toDateTimeString()];

        try {
            $this->db->begin();

            $this->db->execute($sqlDeleteCategory, $params);
            $this->db->execute($sqlDeletePhotos, $params);
            $this->db->execute($sql, $params);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function updateStockBulk(array $products): bool
    {
        try {
            $this->db->begin();

            foreach ($products as $key => $value) {
                $sql = "update
                            products
                        set
                            stock = :stock
                        where id = :id";
                $params = [
                    'stock' => $value['stock'] - $value['quantity'],
                    'id' => $value['id']
                ];

                $this->db->execute($sql, $params);
            }

            $this->db->commit();
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }

    private function whereInBuilder($productsId)
    {
        if (!isset($productsId[0])) return "('')";

        $sqlWhereIdProductIn = "(";
        foreach ($productsId as $key => $value) {
            $sqlWhereIdProductIn .= "'" . $value . "'";

            if (isset($productsId[$key + 1]))
                $sqlWhereIdProductIn .= ",";
            else
                $sqlWhereIdProductIn .= ")";
        }

        return $sqlWhereIdProductIn;
    }
}
