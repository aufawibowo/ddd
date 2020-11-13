<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SellerRegister;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Seller;
use A7Pro\Marketplace\Toko\Core\Domain\Models\SellerId;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\SellerRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\UserRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Services\OtpService;
use A7Pro\Marketplace\Toko\Core\Domain\Services\ProfilePicService;
use A7Pro\Marketplace\Toko\Core\Domain\Services\SmsService;
use A7Pro\Marketplace\Toko\Core\Domain\Services\UrlSignerService;

class SellerRegisterService
{
    private SmsService $smsService;
    private OtpService $otpService;
    private UrlSignerService $urlSignerService;
    private SellerRepository $sellerRepository;
    private ProfilePicService $profilePicService;
    private UserRepository $userRepository;

    public function __construct(
        SmsService $smsService,
        OtpService $otpService,
        UrlSignerService $urlSignerService,
        SellerRepository $sellerRepository,
        ProfilePicService $profilePicService,
        UserRepository $userRepository
    ) {
        $this->smsService = $smsService;
        $this->otpService = $otpService;
        $this->urlSignerService = $urlSignerService;
        $this->sellerRepository = $sellerRepository;
        $this->profilePicService = $profilePicService;
        $this->userRepository = $userRepository;
    }

    public function execute(SellerRegisterRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

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
        } else if ($request->type == 'register') {
            // validate url
            if (!$this->urlSignerService->validate($request->url)) {
                throw new InvalidOperationException('invalid_url');
            }

            //save profile pict here
            $photoname = null;
            if($request->profilePict){
                $photoname = $this->profilePicService->store($request->profilePict);
    
                if (!$photoname)
                    throw new InvalidOperationException("failed_to_upload", 500);
            }
    
            $seller = new Seller(
                (new SellerId())->id(),
                null,
                $request->address,
                null,
                null,
                $request->latitude,
                $request->longitude,
                $request->description,
                null,
                null,
                null,
                $request->email,
                $request->regency,
                $request->postalCode,
                $request->username,
                password_hash($request->password, PASSWORD_BCRYPT),
                $photoname,
                $request->phone,
                $request->userFullName,
                $request->shopName
            );
    
            // validate seller
            $errors = $seller->validate();
    
            if (count($errors) > 0)
                throw new ValidationException($errors);
    
            if (count($this->sellerRepository->getSellerByEmailOrUsername(
                $request->email,
                $request->username,
                $request->phone
            )))
                throw new InvalidOperationException("email_or_username_or_phone_already_used");
    
            // persist
            return $this->sellerRepository->save($seller);
        } else {
            // create otp
            $otp = $this->otpService->generate($request->phone);
            $hash = $otp[1];
            $otp = $otp[0];

            // send otp
            $message = "A7Pro - Jangan memberitahukan kode rahasia ini kepada siapapun termasuk pihak A7Pro. Kode rahasia anda: {$otp}";

            // bypass
            // if (!$this->smsService->send($request->phone, $message)) {
            //     throw new InvalidOperationException('cannot_send_otp_sms');
            // }

            return [
                'signature' => $hash,
                'otp' => $otp
            ];
        }
    }
}
