<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleOrder;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Order;

class ShowSingleOrderDto
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $this->transformOrder($order);
    }

    public function transformOrder(Order $order)
    {
        $obj = new \stdClass();
        $obj->id = $order->getId()->id();
        $obj->courier = $order->getCourier();
        $obj->invoice = $order->getInvoice();
        $obj->products = $order->getProducts();
        $obj->status = Order::getStatusText($order->getStatus());
        $obj->shipping_address = json_decode($order->getShippingAdress());
        $obj->amount_total = $order->getAmountTotal();
        $obj->shipping_total = $order->getShippingTotal();
        $obj->cost_total = $order->getShippingTotal() + $order->getAmountTotal();
        $obj->receipt_no = $order->getReceiptNo();
        $obj->expired_at = $order->getExpiration()->toIsoDateTimeString();
        $obj->created_at = $order->getCreatedAt()->toIsoDateTimeString();
        $obj->updated_at = $order->getUpdatedAt()->toIsoDateTimeString();

        return $obj;
    }
}
