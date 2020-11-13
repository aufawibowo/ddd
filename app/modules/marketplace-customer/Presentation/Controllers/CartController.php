<?php


namespace A7Pro\Marketplace\Customer\Presentation\Controllers;

use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddCatatanKePenjual\AddCatatanKePenjualRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddCatatanKePenjual\AddCatatanKePenjualService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddProductToCart\AddProductToCartRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddProductToCart\AddProductToCartService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\DeleteProductFromCart\DeleteProductFromCartRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\DeleteProductFromCart\DeleteProductFromCartService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\SetQty\SetQtyRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\SetQty\SetQtyService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\ShowCart\ShowCartRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Cart\ShowCart\ShowCartService;
use Exception;

class CartController extends BaseController
{
    public function addAction()
    {
        $customerId = $this->getAuthUserId();
        $productId = $this->request->get('product_id');
        $cartId = $this->request->get('cart_id');

        $request = new AddProductToCartRequest($productId, $customerId, $cartId);

        $service = new AddProductToCartService(
            $this->di->get('cartRepository'),
            $this->di->get('productRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function setQtyAction()
    {
        $customerId = $this->getAuthUserId();
        $productId = $this->request->get('product_id');
        $qty = $this->request->get('qty');
        $cartId = $this->request->get('cart_id');

        $request = new SetQtyRequest($productId, $customerId, $qty, $cartId);

        $service = new SetQtyService(
            $this->di->get('cartRepository'),
            $this->di->get('productRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function deleteAction()
    {
        $customerId = $this->getAuthUserId();
        $cart_id = $this->dispatcher->getParam('cart_id');

        $request = new DeleteProductFromCartRequest($cart_id, $customerId);

        $service = new DeleteProductFromCartService(
            $this->di->get('cartRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function getAction()
    {

        $customerId = $this->getAuthUserId();

        $request = new ShowCartRequest($customerId);

        $service = new ShowCartService(
            $this->di->get('cartRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function addCatatanKePenjualAction()
    {
        $cart_id = $this->request->get('cart_id');
        $catatan = $this->request->get('catatan');

        $request = new AddCatatanKePenjualRequest($cart_id, $catatan);

        $service = new AddCatatanKePenjualService(
            $this->di->get('cartRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}
