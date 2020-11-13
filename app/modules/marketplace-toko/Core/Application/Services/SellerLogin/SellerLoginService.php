<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SellerLogin;

use A7Pro\Marketplace\Toko\Core\Domain\Services\OtpService;
use A7Pro\Marketplace\Toko\Core\Domain\Services\SmsService;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\User;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\UserRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Services\JwtTokenService;

class SellerLoginService
{
    private SmsService $smsService;
    private OtpService $otpService;
    private JwtTokenService $tokenService;
    private UserRepository $userRepository;

    public function __construct(
        SmsService $smsService,
        OtpService $otpService,
        JwtTokenService $tokenService,
        UserRepository $userRepository
    ) {
        $this->smsService = $smsService;
        $this->otpService = $otpService;
        $this->tokenService = $tokenService;
        $this->userRepository = $userRepository;
    }

    public function execute(SellerLoginRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // get user
        $user = $this->getUser($request->id);

        if (is_null($user)) {
            throw new InvalidOperationException('seller_not_found');
        }

        if (!$user->canLogin()) {
            throw new InvalidOperationException('user_is_currently_inactive');
        }

        if (isset($request->password)) {
            return $this->loginWithPassword($user, $request->password);
        } else if (isset($request->otp) && isset($request->otpSignature)) {
            return $this->loginWithOtp($user, $request->id, $request->otp, $request->otpSignature);
        } else {
            return $this->sendOtp($user, $request->id);
        }
    }

    private function sendOtp(User $user, $id)
    {
        // get phone's customer
        $phone = $user->getPhone();

        if (!($phone && $phone->isValid())) {
            throw new InvalidOperationException('invalid_phone');
        }

        // create otp
        $otp = $this->otpService->generate($id);
        $hash = $otp[1];
        $otp = $otp[0];

        // send otp
        $message = "A7Pro - Jangan memberitahukan kode rahasia ini kepada siapapun termasuk pihak A7Pro. Kode rahasia anda: {$otp}";

        // bypass
        // if (!$this->smsService->send($phone->phone(), $message)) {
        //     throw new InvalidOperationException('cannot_send_otp_sms');
        // }

        return [
            'signature' => $hash,
            'otp' => $otp
        ];
    }

    private function loginWithOtp(User $user, string $id, string $otp, string $otpSignature)
    {
        // verify otp
        if (!$this->otpService->verify($otp, $id, $otpSignature)) {
            throw new InvalidOperationException('invalid_otp');
        }

        // create token
        $token = $this->createToken($user->getId());

        return new SellerLoginDto($token, 'Bearer', $user);
    }

    private function loginWithPassword(User $user, string $password)
    {
        // verify password
        if (!$user->verifyPassword($password)) {
            throw new InvalidOperationException('credentials_does_not_match');
        }

        // create token
        $token = $this->createToken($user->getId());

        return new SellerLoginDto($token, 'Bearer', $user);
    }

    private function createToken(string $id): string
    {
        return $this->tokenService->encode($id);
    }

    private function getUser(string $id): ?User
    {
        $user = null;

        if (is_numeric($id)) {
            $user = $this->userRepository->getByPhone($id);
        } else if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userRepository->getByEmail($id);
        } else {
            $user = $this->userRepository->getByUsername($id);
        }

        return $user;
    }
}