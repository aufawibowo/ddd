<?php


namespace A7Pro\Marketplace\Customer\Presentation\Controllers;


use A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\CreateInvoice\CreateInvoiceRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\CreateInvoice\CreateInvoiceService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\CancelOrder\CancelOrderRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\CancelOrder\CancelOrderService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\GetOrder\GetOrderRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\GetOrder\GetOrderService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\OrderReceived\OrderReceivedRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\OrderReceived\OrderReceivedService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\ShowOrders\ShowOrdersRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Order\ShowOrders\ShowOrdersService;
use Exception;

class OrderController extends BaseController
{
    public function orderAction()
    {
        $customerId = $this->getAuthUserId();
        $cartIds = $this->request->get('cart_id') ? : [];
        $productId = $this->request->get('product_id');
        $paymentMethod = $this->request->get('payment_method');
        $shippingAddress = $this->request->get('shipping_address');
        $courierIds = $this->request->get('courier_id');

        $request = new CreateInvoiceRequest(
            $customerId,
            $cartIds,
            $productId,
            $paymentMethod,
            $shippingAddress,
            $courierIds
        );

        $service = new CreateInvoiceService(
            $this->di->get('invoiceRepository'),
            $this->di->get('profileRepository'),
            $this->di->get('cartRepository'),
            $this->di->get('productRepository')
        );

        try {
            $results = $service->execute($request);

            $this->sendOk($results);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function showOrdersAction()
    {
        $customerId = $this->getAuthUserId();
        $limit = $this->request->get('limit');
        $page = $this->request->get('page');

        $request = new ShowOrdersRequest(
            $customerId,
            $limit,
            $page
        );

        $service = new ShowOrdersService(
            $this->di->get('orderRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $results = $service->execute($request);

            $this->sendData($results);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function getAction()
    {
        $customer_id = $this->getAuthUserId();
        $order_id = $this->request->get('order_id');

        $request = new GetOrderRequest($order_id, $customer_id);

        $service = new GetOrderService(
            $this->di->get('orderRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $results = $service->execute($request);

            $this->sendData($results);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function setDoneAction()
    {
        $customerId = $this->getAuthUserId();
        $orderId = $this->request->get('order_id');

        $request = new OrderReceivedRequest($orderId, $customerId);

        $service = new OrderReceivedService($this->di->get('orderRepository'));

        try {
            $results = $service->execute($request);

            $this->sendData($results);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function cancelAction()
    {
        $customerId = $this->getAuthUserId();
        $orderId = $this->dispatcher->getParam('order_id');

        $request = new CancelOrderRequest($orderId, $customerId);

        $service = new CancelOrderService($this->di->get('orderRepository'));

        try {
            $results = $service->execute($request);

            $this->sendData($results);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}