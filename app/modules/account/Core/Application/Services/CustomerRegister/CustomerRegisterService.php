<?php

namespace A7Pro\Account\Core\Application\Services\CustomerRegister;

use A7Pro\Account\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Models\Customer;
use A7Pro\Account\Core\Domain\Models\Date;
use A7Pro\Account\Core\Domain\Models\Phone;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Models\UserId;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;
use A7Pro\Account\Core\Domain\Services\OtpService;
use A7Pro\Account\Core\Domain\Services\SmsService;
use A7Pro\Account\Core\Domain\Services\UrlSignerService;

class CustomerRegisterService
{
    private SmsService $smsService;
    private OtpService $otpService;
    private UrlSignerService $urlSignerService;
    private UserRepository $userRepository;

    public function __construct(
        SmsService $smsService,
        OtpService $otpService,
        UrlSignerService $urlSignerService,
        UserRepository $userRepository
    ) {
        $this->smsService = $smsService;
        $this->otpService = $otpService;
        $this->urlSignerService = $urlSignerService;
        $this->userRepository = $userRepository;
    }

    public function execute(CustomerRegisterRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // check if phone registered
        if ($this->userRepository->isPhoneExists($request->phone)) {
            throw new InvalidOperationException('phone_registered');
        }

        if (isset($request->otp) && isset($request->otpSignature)) {
            // validate otp
            if (!$this->otpService->verify($request->otp, $request->phone, $request->otpSignature)) {
                throw new InvalidOperationException('invalid_otp');
            }

            // create signed url
            $expiration = (new \DateTime())->modify('+1 hour');
            $signedUrl = $this->urlSignerService->sign($request->registrationUrl, $expiration);

            return [
                'url' => $signedUrl
            ];
        } else if (isset($request->url) && isset($request->name) && isset($request->password)) {
            // validate url
            if (!$this->urlSignerService->validate($request->url)) {
                throw new InvalidOperationException('invalid_url');
            }

            // create user customer
            $customer = new User(
                new UserId(),
                $request->name,
                null,
                null,
                new Phone($request->phone, new Date(new \DateTime())),
                null,
                password_hash($request->password, PASSWORD_BCRYPT),
                User::STATUS_ACTIVE,
                [User::ROLE_CUSTOMER],
                new Customer(null, null),
                null
            );

            // validate customer
            $errors = $customer->validate();

            if (count($errors) > 0) {
                throw new ValidationException($errors);
            }

            // persist customer
            return $this->userRepository->add($customer);
        } else {
            // create otp
            $otp = $this->otpService->generate($request->phone);
            $hash = $otp[1];
            $otp = $otp[0];

            // send otp
            $message = "A7Pro - Jangan memberitahukan kode rahasia ini kepada siapapun termasuk pihak A7Pro. Kode rahasia anda: {$otp}";

            if (!$this->smsService->send($request->phone, $message)) {
                throw new InvalidOperationException('cannot_send_otp_sms');
            }

            return [
                'signature' => $hash,
                'otp' => $otp
            ];
        }
    }
}