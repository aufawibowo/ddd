<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use A7Pro\Marketplace\Customer\Core\Domain\Models\OrderId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\OrderRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use PDO;

class SqlOrderRepository implements OrderRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function showOrders(string $customerId, int $limit, int $page): array
    {
        $sql = "select o.id, o.invoice_id, i.code, o.receipt_no, o.status, o.shipping_amount,
                o.seller_id, o.created_at
                from orders o
                inner join invoices i on i.id = o.invoice_id
                where customer_id = :customer_id
                order by o.created_at desc";

        if ($limit)
            $sql .= " limit " . ($page - 1) * $limit . ", " . $limit;

        $orders = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, ['customer_id' => $customerId]);

        foreach ($orders as $key => $value) {
            $sql = "select p.id, p.name, pp.photo_url, op.quantity, op.amount,
                    op.quantity*p.weight as weight_total, c.catatan as note, op.is_rated
                    from order_products op
                    inner join products p on p.id = op.product_id
                    inner join cart c on c.id = op.cart_id
                    left join product_photos pp on pp.id = p.product_pict
                    where op.order_id = :order_id";
            $orders[$key]['products'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, [
                'order_id' => $value['id']
            ]);
            $orders[$key]['status'] = Order::getStatusText($value['status']);

            foreach ($orders[$key]['products'] as $key2 => $value2){
                $orders[$key]['amount_total'] += $value2['amount'];
                $orders[$key]['cost_total'] += $value2['amount'];
            }
        }

        return $orders;
    }

    public function isOrderExist(string $orderId, string $customerId, ?int $status): bool
    {
        $sql = "select 1 as `exist`
                from orders
                where
                    id = :order_id
                    and customer_id = :customer_id";
        $params = [
            'order_id' => $orderId,
            'customer_id' => $customerId
        ];

        if($status){
            $sql .= " and status = :status";
            $params['status'] = $status;
        }

        if($this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params))
            return true;

        return false;
    }
  
    public function get(OrderId $orderId, string $customerId)
    {
        $sql = "select distinct 
                    o.id, 
                    o.invoice_id, 
                    i.code, 
                    s.name as seller_name,
                    c2.name as courier_name,
                    i.amount as order_amount,
                    sp.address as shipping_profile_address,
                    sp.label as shipping_profile_label,
                    sp.nama_penerima as shipping_profile_penerima,
                    sp.nomor_hp_penerima as shipping_profile_hp_penerima,
                    o.receipt_no, 
                    o.status as status_order, 
                    o.shipping_amount,
                    o.seller_id, 
                    o.created_at
                from orders o
                inner join invoices i on i.id = o.invoice_id
                inner join sellers s on o.seller_id = s.user_id
                inner join couriers c2 on o.courier_id = c2.id  
                inner join customers c on o.customer_id = c.user_id
                inner join shipping_profile sp on c.user_id = sp.user_id
                where 
                      o.id = :order_id
                      and
                      c.user_id = :customer_id";

        $orders = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, ['order_id' => $orderId->id(), 'customer_id' => $customerId]);

        foreach ($orders as $key => $value) {
            if($key == 0){
                $orders[$key]['amount_total'] = 0;
                $orders[$key]['cost_total'] = 0;
            }

            $sql = "select 
                        p.id, 
                        p.name, 
                        p.price,
                        pp.photo_url, 
                        op.quantity, 
                        op.amount,
                        op.quantity*p.weight as weight_total, 
                        c.catatan as note
                    from order_products op
                    inner join products p on p.id = op.product_id
                    inner join cart c on c.id = op.cart_id
                    left join product_photos pp on pp.id = p.product_pict
                    where op.order_id = :order_id";
            $orders[$key]['products'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, [
                'order_id' => $orderId->id()
            ]);
            $orders[$key]['status_order'] = Order::getStatusText($value['status_order']);

            foreach ($orders[$key]['products'] as $key => $value){
                $orders[$key]['amount_total'] += $value['amount'];
                $orders[$key]['cost_total'] += $value['amount'];
            }
        }

        return $orders;
    }

    public function cancelOrder(string $orderId): bool
    {
        $sql = "update orders set status = :status where id = :id";

        $params = [
            'status' => Order::STATUS_CANCELLED,
            'id' => $orderId,
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

    public function setDone(OrderId $orderId)
    {
        $sql = "update orders set status = :status where id = :id";

        $params = [
            'status' => Order::STATUS_RECEIVED,
            'id' => $orderId->id(),
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
}
