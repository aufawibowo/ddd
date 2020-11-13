<?php

namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\SellerLogin\SellerLoginRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\SellerLogin\SellerLoginService;

class LoginController extends BaseController
{
    public function sellerLoginAction()
    {
        $id = $this->request->get('id');
        $otp = $this->request->get('otp');
        $otpSignature = $this->request->get('signature');
        $password = $this->request->get('password');

        $request = new SellerLoginRequest($id, $otpSignature, $otp, $password);
        $service = new SellerLoginService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('tokenService'),
            $this->di->get('userRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}