<?php

namespace A7Pro\Account\Presentation\Controllers;

use A7Pro\Account\Core\Application\Services\AdminLogin\AdminLoginRequest;
use A7Pro\Account\Core\Application\Services\AdminLogin\AdminLoginService;
use A7Pro\Account\Core\Application\Services\CustomerLogin\CustomerLoginRequest;
use A7Pro\Account\Core\Application\Services\CustomerLogin\CustomerLoginService;
use A7Pro\Account\Core\Application\Services\GetUserById\GetUserByIdRequest;
use A7Pro\Account\Core\Application\Services\GetUserById\GetUserByIdService;
use A7Pro\Account\Core\Application\Services\TechnicianLogin\TechnicianLoginRequest;
use A7Pro\Account\Core\Application\Services\TechnicianLogin\TechnicianLoginService;

class LoginController extends BaseController
{
    public function getAuthenticatedUserAction()
    {
        $userId = $this->getAuthUserId();

        $request = new GetUserByIdRequest($userId);
        $service = new GetUserByIdService($this->di->get('userRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function customerLoginAction()
    {
        $id = $this->request->get('id');
        $otp = $this->request->get('otp');
        $otpSignature = $this->request->get('signature');
        $password = $this->request->get('password');

        $request = new CustomerLoginRequest($id, $otpSignature, $otp, $password);
        $service = new CustomerLoginService(
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

    public function technicianLoginAction()
    {
        $id = $this->request->get('id');
        $otp = $this->request->get('otp');
        $otpSignature = $this->request->get('signature');
        $password = $this->request->get('password');

        $request = new TechnicianLoginRequest($id, $otpSignature, $otp, $password);
        $service = new TechnicianLoginService(
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

    public function adminLoginAction()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $request = new AdminLoginRequest($username, $password);
        $service = new AdminLoginService(
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