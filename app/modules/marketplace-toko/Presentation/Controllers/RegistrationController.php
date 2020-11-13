<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\SellerRegister\SellerRegisterRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\SellerRegister\SellerRegisterService;

class RegistrationController extends BaseController
{
    public function sellerOtpAction()
    {
        $phone = $this->request->get('phone');
        $otp = $this->request->get('otp');
        $otpSignature = $this->request->get('signature');

        $config = $this->di->get('config');
        $baseUrl = $config->path('app.baseUrl');
        $registrationUrl = $baseUrl . '/marketplace-toko/register/' . $phone;

        $request = new SellerRegisterRequest(
            $phone,
            $otpSignature,
            $otp,
            $registrationUrl,
            null,
            'otp',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );

        $service = new SellerRegisterService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('urlSignerService'),
            $this->di->get('sellerRepository'),
            $this->di->get('profilePicService'),
            $this->di->get('userRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function sellerRegisterAction()
    {
        $phone = $this->dispatcher->getParam('phone');
        $shopName = $this->request->get('shop_name');
        $userFullName = $this->request->get('user_full_name');
        $email = $this->request->get('email');
        $regency = $this->request->get('regency');
        $postalCode = $this->request->get('postal_code');
        $latitude = (float) $this->request->get('latitude');
        $longitude = (float) $this->request->get('longitude');
        $address = $this->request->get('address');
        $username = $this->request->get('username');
        $password = $this->request->get('password');
        $passwordConfirmation = $this->request->get('password_confirmation');
        $description = $this->request->get('description');
        $profilePict = $this->request->getUploadedFiles()[0];

        $config = $this->di->get('config');
        $baseUrl = $config->path('app.baseUrl');
        $url = $baseUrl . $this->request->getURI();

        $request = new SellerRegisterRequest(
            $phone,
            null,
            null,
            null,
            $url,
            'register',
            $shopName,
            $userFullName,
            $email,
            $regency,
            $postalCode,
            $latitude,
            $longitude,
            $address,
            $username,
            $password,
            $passwordConfirmation,
            $description,
            $profilePict
        );

        $service = new SellerRegisterService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('urlSignerService'),
            $this->di->get('sellerRepository'),
            $this->di->get('profilePicService'),
            $this->di->get('userRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
