<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Date;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\OrderRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Order;
use A7Pro\Marketplace\Toko\Core\Domain\Models\OrderId;
use PDO;

class SqlOrderRepository implements OrderRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getOrdersList(
        string $sellerId,
        int $page,
        int $limit,
        ?int $status,
        ?string $keyword
    ): array
    {
        $sql = "select distinct
                    o.id, o.invoice_id, i.code, i.status as invoice_status,
                    o.customer_id, cu.name as customer_name,
                    o.courier_id, c.name as courier_name, o.expired_at,
                    p.id as product_id, p.name as product_name, op.quantity,
                    pp.photo_url,
                    o.status as order_status, o.created_at, o.updated_at
                from 
                    orders o
                inner join
                    invoices i on o.invoice_id = i.id
                inner join
                    couriers c on c.id = o.courier_id
                inner join
                    users cu on cu.id = o.customer_id
                inner join
                    order_products op on op.order_id = o.id
                inner join
                    products p on p.id = op.product_id
                left join
                    product_photos pp on pp.id = p.product_pict
                where o.deleted_at is null
                    and o.seller_id = :seller_id";

        if ($status)
            $sql .= " and o.status = " . $status;
        if($keyword)
            $sql .= " and (
                cu.name like '%" . $status . "%'
                or i.code  like '%" . $status . "%'
                or o.receipt_no like '%" . $status . "%'
                or p.name like '%" . $status . "%'
            )";

        $sql .= " order by o.created_at desc";
        if ($limit)
            $sql .= " limit " . ($page - 1) * $limit . ", " . $limit;

        $params['seller_id'] = $sellerId;

        $results = $this->db->fetchAll($sql, \PDO::FETCH_ASSOC, $params);

        // transform data
        $orders = [];
        $orderIdsIndex = [];
        foreach ($results as $key => $value) {
            $product = [
                'id' => $value['product_id'],
                'name' => $value['product_name'],
                'quantity' => $value['quantity'],
                'photo_url' => $value['photo_url']
            ];

            if(!array_key_exists($value['id'], $orderIdsIndex)){
                $orderIdsIndex[$value['id']] = count($orders);
                
                unset(
                    $value['product_name'],
                    $value['product_id'],
                    $value['quantity'],
                    $value['photo_url']
                );
                $orders[$orderIdsIndex[$value['id']]] = $value;
            }

            $orders[$orderIdsIndex[$value['id']]]['products'][] = $product;

            $orders[$key]['order_status'] = Order::getStatusText($results[$key]['order_status']);
            
            // $sql = "select
            //             quantity, name, amount
            //         from
            //             order_products
            //         inner join
            //             products p on p.id = product_id
            //         where order_id = :order_id";
            // $param = ['order_id' => $value['id']];
            // $results[$key]['products'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);
        }

        return $orders;
    }

    public function getOrderById(string $orderId): ?Order
    {
        $sql = "select
                    i.id as invoice_id, i.code, i.status as invoice_status,
                    i.shipping_address,
                    o.customer_id, cu.name as customer_name,
                    o.courier_id, c.name as courier_name, o.shipping_amount,
                    o.seller_id, o.receipt_no, o.expired_at,
                    o.id, o.status as order_status, o.created_at, o.updated_at
                from 
                    orders o
                inner join
                    invoices i on o.invoice_id = i.id
                inner join
                    couriers c on c.id = o.courier_id
                inner join
                    users cu on cu.id = o.customer_id
                where o.deleted_at is null
                    and o.id = :id
                order by o.created_at desc";
        $param = ['id' => $orderId];
        $order = $this->db->fetchOne($sql, \PDO::FETCH_ASSOC, $param);

        // get products
        $sql = "select
                    p.id, p.name, p.stock, op.quantity, op.quantity*p.weight as total_weight,
                    op.amount, c.catatan as note, pp.photo_url
                from
                    order_products op
                inner join
                    products p on p.id = op.product_id
                inner join
                    cart c on c.id = op.cart_id
                left join
                    product_photos pp on pp.id = p.product_pict
                where
                    op.order_id = :id";
        $products = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        $amountTotal = 0;
        foreach ($products as $key => $value)
            $amountTotal += $value['amount'];

        if ($order) {
            $courier = [
                'id' => $order['courier_id'],
                'name' => $order['courier_name']
            ];

            $invoice = [
                'id' => $order['invoice_id'],
                'code' => $order['code'],
                'status' => $order['invoice_status']
            ];

            $customer = [
                'id' => $order['customer_id'],
                'name' => $order['customer_name']
            ];

            return new Order(
                new OrderId($order['id']),
                $order['seller_id'],
                $order['order_status'],
                new Date(new \DateTime($order['created_at'])),
                new Date(new \DateTime($order['updated_at'])),
                $products,
                $invoice,
                $courier,
                $customer,
                $order['receipt_no'],
                $order['shipping_address'],
                $amountTotal,
                $order['shipping_amount'],
                new Date(new \DateTime($order['expired_at']))
            );
        }

        return null;
    }

    public function updateStatusOrder(Order $order, string $receiptNo = ""): bool
    {
        $sql = "update
                    orders
                set
                    status = :status";

        if (
            $order->getStatus() == Order::STATUS_PREPARING
            && $receiptNo
        )
            $sql .= ", receipt_no = '" . $receiptNo . "'";

        $sql .= " where id = :id";
        $params = [
            'status' => $order->getOrderNextStatus($receiptNo),
            'id' => $order->getId()->id()
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
