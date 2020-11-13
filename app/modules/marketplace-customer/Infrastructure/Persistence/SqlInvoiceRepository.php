<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use A7Pro\Marketplace\Customer\Core\Domain\Models\Invoice;
use A7Pro\Marketplace\Customer\Core\Domain\Models\InvoiceId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\InvoiceRepository;
use PDO;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use Ramsey\Uuid\Uuid;

class SqlInvoiceRepository implements InvoiceRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function save(Invoice $invoice, Order $order): bool
    {
        if($order->getProductId()){
            $sqlAddToCart = "insert into cart
                    (id, product_id, customer_id, qty)
                    values
                    (:id, :product_id, :customer_id, :qty)";
            $paramsAddToCart = [
                'id' => Uuid::uuid4()->toString(),
                'product_id' => $order->getProductId(),
                'customer_id' => $order->getCustomerId(),
                'qty' => 1
            ];

            try {
                //code...
                $this->db->begin();

                $this->db->execute($sqlAddToCart, $paramsAddToCart);
            } catch (\Throwable $th) {
                var_dump($th->getMessage());
                $this->db->rollback();

                return false;
            }

            $order->setCart([ $paramsAddToCart['id'] ]);
        }

        $sqlGetOrderProducts = "select c.id, c.product_id, p.stock, c.qty, p.price, p.seller_id
            from cart c
            inner join products p on p.id = c.product_id
            where c.id in " . $this->whereInBuilder($order->getCart());
        $sqlGetOrderProducts .= "
            and c.deleted_at is null
            and c.is_checked_out != 1
            and p.deleted_at is null";
        $orderProducts = $this->db->fetchAll($sqlGetOrderProducts, PDO::FETCH_ASSOC);

        if(count($orderProducts) < 1)
            return false;

        $orderIds = [];
        $amountTotal = 0;
        $sqlOrderProducts = "insert into order_products (id, order_id, product_id, quantity, amount, cart_id) values ";
        $sqlUpdateStockProducts = [];
        foreach ($orderProducts as $key => $value) {
            if(!array_key_exists($value['seller_id'], $orderIds))
                $orderIds[$value['seller_id']] = Uuid::uuid4()->toString();

            $amount = $value['price'] * $value['qty'];
            $amountTotal += $amount;
            $sqlOrderProducts .= "(
                '" . Uuid::uuid4()->toString() ."',
                '" . $orderIds[$value['seller_id']] . "',
                '" . $value['product_id'] . "',
                '" . $value['qty'] . "',
                '" . $amount . "',
                '" . $value['id'] . "'
            )";

            $remainingStock = (int) $value['stock'] - (int) $value['qty'];
            // generate update stock query
            $sqlUpdateStockProducts[] = "
                    update products
                    set stock = '". $remainingStock ."'
                    where id = '". $value['product_id'] ."'
                ";

            if ($orderProducts[$key + 1]) $sqlOrderProducts .= ",";
        }

        $sqlOrders = "insert into orders (id, invoice_id, seller_id, customer_id, courier_id, status, expired_at) values ";
        foreach ($orderIds as $key => $value) {
            $sqlOrders .= "(
                '" . $value ."',
                '" . $invoice->getInvoiceId() . "',
                '" . $key . "',
                '" . $order->getCustomerId() . "',
                '" . $order->getCourierIds()[$key] . "',
                '" . $order->getStatus() . "',
                '" . $invoice->getExpiration() . "'
            )";

            if ($orderIds[$key + 1]) $sqlOrders .= ",";
        }

        $sqlInvoice = "insert into invoices
                (id, code, amount, status, payment_method, expiration, shipping_address, user_id)
                values
                (:id, :code, :amount, :status, :payment_method, :expiration, :shipping_address, :user_id)
                ";

        $paramsInvoice = [
            'id' => $invoice->getInvoiceId(),
            'code' => $invoice->getCode(),
            'amount' => $amountTotal,
            'status' => $invoice->getStatus(),
            'payment_method' => $invoice->getPaymentMethod(),
            'expiration' => $invoice->getExpiration(),
            'shipping_address' => $invoice->getShippingAddress(),
            'user_id' => $invoice->getCustomerId()
        ];

        $sqlUpdateCart = "update cart
            set is_checked_out = 1,
            checked_out_at = :checked_out_at
            where id in " . $this->whereInBuilder($order->getCart());
        $params = ['checked_out_at' => date('Y-m-d H:i:s')];

        try {
            if(!$order->getProductId())
                $this->db->begin();

            $this->db->execute($sqlInvoice, $paramsInvoice);
            $this->db->execute($sqlOrders);
            $this->db->execute($sqlOrderProducts);
            
            // update stock
            foreach ($sqlUpdateStockProducts as $key => $value)
                $this->db->execute($value);

            // update cart checked_out
            $this->db->execute($sqlUpdateCart, $params);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function get(InvoiceId $invoiceId)
    {
        $sql = "select * from invoices where id = :invoiceId";

        $params = [
            'invoiceId' => $invoiceId->id()
        ];

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        return $results;
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