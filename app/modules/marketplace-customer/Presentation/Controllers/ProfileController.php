<?php


namespace A7Pro\Marketplace\Customer\Presentation\Controllers;


use A7Pro\Marketplace\Customer\Core\Application\Services\Profile\AddShippingAddress\AddShippingAddressRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Profile\AddShippingAddress\AddShippingAddressService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Profile\GetShippingAddress\GetShippingAddressRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Profile\GetShippingAddress\GetShippingAddressService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Profile\ShowProfile\ShowProfileRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Profile\ShowProfile\ShowProfileService;
use Exception;

class ProfileController extends BaseController
{
    public function getProfileAction()
    {
        $customerId = $this->getAuthUserId();

        $request = new ShowProfileRequest($customerId);

        $service = new ShowProfileService(
            $this->di->get('profileRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function addAddressAction()
    {
        $customerId = $this->getAuthUserId();
        $new_address = $this->request->get('new_address');
        $nama_penerima = $this->request->get('nama_penerima');
        $nomor_hp_penerima = $this->request->get('nomor_hp_penerima');
        $latitude = $this->request->get('latitude');
        $longitude = $this->request->get('longitude');
        $label = $this->request->get('label');

        $request = new AddShippingAddressRequest(
            $customerId,
            $new_address,
            $nama_penerima,
            $nomor_hp_penerima,
            $latitude,
            $longitude,
            $label
        );

        $service = new AddShippingAddressService(
            $this->di->get('profileRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function getAddressAction()
    {
        $customerId = $this->getAuthUserId();

        $request = new GetShippingAddressRequest($customerId);

        $service = new GetShippingAddressService(
            $this->di->get('profileRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}