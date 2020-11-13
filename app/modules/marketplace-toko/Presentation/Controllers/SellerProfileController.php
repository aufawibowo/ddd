<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfile\ShowSellerProfileRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfile\ShowSellerProfileService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfileById\ShowSellerProfileByIdRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfileById\ShowSellerProfileByIdService;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateSellerProfile\UpdateSellerProfileRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateSellerProfile\UpdateSellerProfileService;

class SellerProfileController extends BaseController
{
    public function showSellerProfileAction()
    {
        $sellerId = $this->getAuthUserId();

        $request = new ShowSellerProfileRequest($sellerId);

        $service = new ShowSellerProfileService(
            $this->di->get('sellerRepository'),
            $this->di->get('profilePicService')
        );

        try {
            $profile = $service->execute($request);

            $this->sendData($profile);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function showSellerProfileByIdAction()
    {
        $sellerId = $this->getAuthUserId();
		$profileId = $this->dispatcher->getParam('profile_id');

        $request = new ShowSellerProfileByIdRequest($sellerId, $profileId);

        $service = new ShowSellerProfileByIdService(
            $this->di->get('sellerRepository'),
            $this->di->get('profilePicService')
        );

        try {
            $profile = $service->execute($request);

            $this->sendData($profile);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateSellerProfileAction()
    {
        $sellerId = $this->getAuthUserId();
        $gender = $this->request->get('gender');
        $location = $this->request->get('location');
        $placeOfBirth = $this->request->get('place_of_birth');
        $dateOfBirth = $this->request->get('date_of_birth');
        $latitude = $this->request->get('latitude');
        $longitude = $this->request->get('longitude');
        $description = $this->request->get('description');
        $workingDays = array_map('intval', explode(",", $this->request->get('working_days')));
        $openingHours = $this->request->get('opening_hours');
        $closingHours = $this->request->get('closing_hours');
        $email = $this->request->get('email');

        $request = new UpdateSellerProfileRequest(
            $sellerId,
            $gender,
            $location,
            $placeOfBirth,
            $dateOfBirth,
            $latitude,
            $longitude,
            $description,
            $workingDays,
            $openingHours,
            $closingHours,
            $email
        );

        $service = new UpdateSellerProfileService(
            $this->di->get('sellerRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
