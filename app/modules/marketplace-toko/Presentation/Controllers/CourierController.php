<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\ShowCouriers\ShowCouriersRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowCouriers\ShowCouriersService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowCouriersOnCustomerCheckout\ShowCouriersOnCustomerCheckoutRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowCouriersOnCustomerCheckout\ShowCouriersOnCustomerCheckoutService;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateCouriers\UpdateCouriersRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateCouriers\UpdateCouriersService;

class CourierController extends BaseController
{
    public function showCouriersAction()
    {
        $sellerId = $this->getAuthUserId();

        $request = new ShowCouriersRequest($sellerId);

        $service = new ShowCouriersService(
            $this->di->get('courierRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function showCouriersOnCustomerCheckoutAction()
    {
        $sellerId = $this->getAuthUserId();

        $request = new ShowCouriersOnCustomerCheckoutRequest($sellerId);

        $service = new ShowCouriersOnCustomerCheckoutService(
            $this->di->get('courierRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateSellerCouriersAction()
    {
        $sellerId = $this->getAuthUserId();
        $couriers = $this->request->get('couriers_id');

        $request = new UpdateCouriersRequest($sellerId, $couriers);

        $service = new UpdateCouriersService(
            $this->di->get('courierRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
