<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\SearchOrders\SearchOrdersRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\SearchOrders\SearchOrdersService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowOrders\ShowOrdersRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowOrders\ShowOrdersService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleOrder\ShowSingleOrderRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleOrder\ShowSingleOrderService;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateOrderStatus\UpdateOrderStatusRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateOrderStatus\UpdateOrderStatusService;

class OrderController extends BaseController
{
    public function showOrdersAction()
    {
        $sellerId = $this->getAuthUserId();
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $status = $this->request->get('status');

        $request = new ShowOrdersRequest($sellerId, $page, $limit, $status);

        $service = new ShowOrdersService(
            $this->di->get('orderRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    public function searchOrdersAction()
    {
        $sellerId = $this->getAuthUserId();
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $keyword = $this->request->get('keyword');

        $request = new SearchOrdersRequest($sellerId, $page, $limit, $keyword);

        $service = new SearchOrdersService(
            $this->di->get('orderRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function showSingleOrderAction()
    {
        $sellerId = $this->getAuthUserId();
        $orderId = $this->dispatcher->getParam('order_id');

        $request = new ShowSingleOrderRequest($sellerId, $orderId);

        $service = new ShowSingleOrderService(
            $this->di->get('orderRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateOrderStatusAction()
    {
        $sellerId = $this->getAuthUserId();
        $orderId = $this->dispatcher->getParam('order_id');
        $receiptNo = $this->request->get('receipt_no') ?: "";

        $request = new UpdateOrderStatusRequest($sellerId, $orderId, $receiptNo);

        $service = new UpdateOrderStatusService(
            $this->di->get('orderRepository'),
            $this->di->get('productRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
