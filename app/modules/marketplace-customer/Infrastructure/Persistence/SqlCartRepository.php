<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use A7Pro\Marketplace\Customer\Core\Domain\Models\Cart;
use A7Pro\Marketplace\Customer\Core\Domain\Models\CartId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Date;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use PDO;

class SqlCartRepository extends SqlBaseRepository implements CartRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function addNew(Cart $cart): bool
    {
        $sql = "select id, qty
                from cart
                where product_id = :product_id
                    and customer_id = :customer_id
                    and deleted_at is null
                    and is_checked_out = 0";

        $params = [
            'product_id' => $cart->getProductId(),
            'customer_id' => $cart->getCustomerId(),
        ];

        $select = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if ($select) {
            $sql = "update cart
                    set qty = :qty
                    where id = :id";
            $params = [
                'qty' => $select['qty'] + 1,
                'id' => $select['id']
            ];
        } else {
            $sql = "insert into cart
                    (id, product_id, customer_id, qty)
                values
                    (:id, :product_id, :customer_id, :qty)";
            $params = [
                'id' => $cart->getCartId()->id(),
                'product_id' => $cart->getProductId(),
                'customer_id' => $cart->getCustomerId(),
                'qty' => $cart->getQty(),
            ];
        }

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

    public function get(string $customerId, string $seller_id)
    {
        $sql = "select distinct 
                    c.id as cart_id, 
                    c.qty as qty, 
                    p.id as product_id, 
                    p.name as product_name, 
                    p.price as product_price, 
                    pp.photo_url as photo_product, 
                    s.name as seller_name, 
                    u.profile_pict as seller_profile_picture_url, 
                    s.user_id as seller_id, 
                    c.catatan
                from cart c
                inner join products p on c.product_id = p.id
                inner join product_photos pp on p.id = pp.product_id
                inner join sellers s on p.seller_id = s.user_id
                inner join users u on s.user_id = u.id
                where
                    customer_id = :customer_id
                    and
                    is_checked_out = '0'      
                    and
                    p.seller_id = :seller_id
                group by 
                    s.user_id";

        $params = [
            'customer_id' => $customerId,
            'seller_id'   => $seller_id
        ];

        $cart = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        foreach ($cart as $key => $value)
        {
            $carts[] = [
                'cart_id'                       => $value['cart_id'],
                'qty'                           => $value['qty'],
                'product_id'                    => $value['product_id'],
                'product_name'                  => $value['product_name'],
                'product_price'                 => $value['product_price'],
                'photo_product'                 => $value['photo_product'],
                'is_product_available'          => $this->isProductAvailable($value['product_id']),
                'seller_name'                   => $value['seller_name'],
                'seller_profile_picture_url'    => $value['seller_profile_picture_url'],
                'seller_id'                     => $value['seller_id'],
                'catatan'                       => $value['catatan'],
            ];
        }

        return $carts;
    }

    public function getSellerId(string $customerId)
    {
        $sql = "select distinct 
                    s.user_id as seller_id,
                    s.name as seller_name
                from cart c
                inner join products p on c.product_id = p.id
                inner join sellers s on p.seller_id = s.user_id
                where
                    c.customer_id = :customer_id
                    and
                    c.is_checked_out = '0'";

        $params = ['customer_id' => $customerId];

        return $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);
    }

    public function delete(string $cartId, string $customerId)
    {
        $sql = "delete 
                    from cart
                where
                    id = :id
                    and
                    customer_id = :customer_id
                    ";

        $params = [
            'id' => $cartId,
            'customer_id' => $customerId
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

    public function checkOut(array $productId, string $customerId)
    {

        $sql = "update 
                    cart
                set
                    is_checked_out = 1,
                    checked_out_at = :checked_out_date
                where
                    id in " . $this->whereInBuilder($productId);

        $sql .= "and customer_id = :customer_id";

        $checked_out_date = new Date(new \DateTime());
        $params = [
            'customer_id' => $customerId,
            'checked_out_date' => $checked_out_date->toDateTimeString()
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

    public function addCatatanKePenjual(CartId $id, string $catatan)
    {
        $sql = "update cart
                set catatan = :catatan
                where id = :id
                and
                is_checked_out = '0'";

        $params = [
            'id' => $id->id(),
            'catatan' => $catatan
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

    public function checkProductStock(array $cartIds): array
    {
        $sql = "select c.product_id, p.stock, c.qty
                from cart c
                inner join products p on p.id = c.product_id
                where c.id in " . $this->whereInBuilder($cartIds);
        $stocks = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $insufficientProductStock = [];
        foreach ($stocks as $key => $value)
            if ($value['stock'] < $value['qty'])
                $insufficientProductStock[] = $value['product_id'];

        return $insufficientProductStock;
    }

    public function set(string $cartId, string $customerId, string $qty)
    {
        $sql = "update cart 
                    set qty = :qty 
                    where id = :cart_id
                        and 
                            customer_id = :customer_id";

        $params = [
            'customer_id' => $customerId,
            'qty'        => $qty,
            'cart_id'    => $cartId
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

    public function isProductInCart(string $productId, string $customerId)
    {
        $sql = "select * from cart where product_id = :product_id and customer_id = :customer_id";

        $params = [
            'product_id' => $productId,
            'customer_id' => $customerId
        ];

        $product = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if (!$product) {
            return false;
        } else {
            return true;
        }
    }

    public function addOne(string $productId, string $customerId, string $cartId)
    {
        $sql = "select * from cart where product_id = :product_id and customer_id = :customer_id";

        $params = [
            'product_id' => $productId,
            'customer_id' => $customerId
        ];

        $product = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        $sql = "update cart 
                    set qty = :qty 
                    where product_id = :product_id 
                      and customer_id = :customer_id
                      and id = :cart_id";

        $params = [
            'product_id' => $productId,
            'customer_id' => $customerId,
            'qty'        => $product['qty']+1,
            'cart_id'   => $cartId
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

    private function isProductAvailable($id)
    {
        $sql = "select * from products where is_active = '1' and stock >= 1 and id = :id";

        $param = ['id'  => $id];

        $isAvailable = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        if (!$isAvailable)
        {
            return false;
        }
        else {
            return true;
        }
    }
}
