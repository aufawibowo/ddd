<?php

namespace A7Pro\Account\Presentation\Controllers;

use A7Pro\Account\Core\Application\Services\CustomerRegister\CustomerRegisterRequest;
use A7Pro\Account\Core\Application\Services\CustomerRegister\CustomerRegisterService;
use A7Pro\Account\Core\Application\Services\TechnicianRegister\TechnicianRegisterRequest;
use A7Pro\Account\Core\Application\Services\TechnicianRegister\TechnicianRegisterService;

class RegistrationController extends BaseController
{
    public function customerOtpAction()
    {
        $phone = $this->request->get('phone');
        $otp = $this->request->get('otp');
        $otpSignature = $this->request->get('signature');

        $config = $this->di->get('config');
        $baseUrl = $config->path('app.baseUrl');
        $registrationUrl = $baseUrl . '/account/customer/registration/' . $phone;

        $request = new CustomerRegisterRequest(
            $phone,
            $otpSignature,
            $otp,
            $registrationUrl,
            null,
            null,
            null
        );

        $service = new CustomerRegisterService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('urlSignerService'),
            $this->di->get('userRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function customerRegisterAction()
    {
        $phone = $this->dispatcher->getParam('phone');
        $name = $this->request->get('name');
        $password = $this->request->get('password');

        $config = $this->di->get('config');
        $baseUrl = $config->path('app.baseUrl');
        $url = $baseUrl . $this->request->getURI();

        $request = new CustomerRegisterRequest(
            $phone,
            null,
            null,
            null,
            $url,
            $name,
            $password
        );

        $service = new CustomerRegisterService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('urlSignerService'),
            $this->di->get('userRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function technicianOtpAction()
    {
        $apituMemberId = $this->request->get('apitu_member_id');
        $otp = $this->request->get('otp');
        $otpSignature = $this->request->get('signature');

        $config = $this->di->get('config');
        $baseUrl = $config->path('app.baseUrl');
        $registrationUrl = $baseUrl . '/account/technician/registration/' . $apituMemberId;

        $request = new TechnicianRegisterRequest(
            $apituMemberId,
            $otpSignature,
            $otp,
            $registrationUrl,
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
        );

        $service = new TechnicianRegisterService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('urlSignerService'),
            $this->di->get('apituService'),
            $this->di->get('userRepository'),
            $this->di->get('dpcRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function technicianRegisterAction()
    {
        $apituMemberId = $this->dispatcher->getParam('apitu_member_id');
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $address = $this->request->get('address');
        $area = $this->request->get('area');
        $city = $this->request->get('city');
        $zipCode = $this->request->get('zip_code');
        $latitude = $this->request->get('latitude');
        $longitude = $this->request->get('longitude');

        $config = $this->di->get('config');
        $baseUrl = $config->path('app.baseUrl');
        $url = $baseUrl . $this->request->getURI();

        $request = new TechnicianRegisterRequest(
            $apituMemberId,
            null,
            null,
            null,
            $url,
            $name,
            $email,
            $password,
            $address,
            $area,
            $city,
            $zipCode,
            $latitude,
            $longitude
        );

        $service = new TechnicianRegisterService(
            $this->di->get('smsService'),
            $this->di->get('otpService'),
            $this->di->get('urlSignerService'),
            $this->di->get('apituService'),
            $this->di->get('userRepository'),
            $this->di->get('dpcRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}