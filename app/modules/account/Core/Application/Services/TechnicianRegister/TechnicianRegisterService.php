<?php

namespace A7Pro\Account\Core\Application\Services\TechnicianRegister;

use A7Pro\Account\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Models\Address;
use A7Pro\Account\Core\Domain\Models\Coordinate;
use A7Pro\Account\Core\Domain\Models\Date;
use A7Pro\Account\Core\Domain\Models\Email;
use A7Pro\Account\Core\Domain\Models\Phone;
use A7Pro\Account\Core\Domain\Models\Technician;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Models\UserId;
use A7Pro\Account\Core\Domain\Models\ZipCode;
use A7Pro\Account\Core\Domain\Repositories\DpcRepository;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;
use A7Pro\Account\Core\Domain\Services\ApituService;
use A7Pro\Account\Core\Domain\Services\OtpService;
use A7Pro\Account\Core\Domain\Services\SmsService;
use A7Pro\Account\Core\Domain\Services\UrlSignerService;

class TechnicianRegisterService
{
    private SmsService $smsService;
    private OtpService $otpService;
    private UrlSignerService $urlSignerService;
    private ApituService $apituService;
    private UserRepository $userRepository;
    private DpcRepository $dpcRepository;

    public function __construct(
        SmsService $smsService,
        OtpService $otpService,
        UrlSignerService $urlSignerService,
        ApituService $apituService,
        UserRepository $userRepository,
        DpcRepository $dpcRepository
    ) {
        $this->smsService = $smsService;
        $this->otpService = $otpService;
        $this->urlSignerService = $urlSignerService;
        $this->apituService = $apituService;
        $this->userRepository = $userRepository;
        $this->dpcRepository = $dpcRepository;
    }

    public function execute(TechnicianRegisterRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if ($this->userRepository->isApituMemberIdExists($request->apituMemberId)) {
            throw new InvalidOperationException('apitu_member_id_registered');
        }

        if ($request->isVerifyOtp()) {
            // verify otp
            if (!$this->otpService->verify($request->otp, $request->apituMemberId, $request->otpSignature)) {
                throw new InvalidOperationException('invalid_otp');
            }

            // get member from apitu service
            $apituMember = $this->apituService->getMemberByMemberId($request->apituMemberId);

            if (is_null($apituMember)) {
                throw new InvalidOperationException('apitu_member_not_found');
            }

            // create signed url
            $expiration = (new \DateTime())->modify('+1 hour');
            $signedUrl = $this->urlSignerService->sign($request->registrationUrl, $expiration);

            return [
                'url' => $signedUrl,
                'technician' => new ApituMemberDto($apituMember)
            ];
        } else if ($request->isRegister()) {
            // validate url
            if (!$this->urlSignerService->validate($request->url)) {
                throw new InvalidOperationException('invalid_url');
            }

            // get member from apitu service
            $apituMember = $this->apituService->getMemberByMemberId($request->apituMemberId);

            if (is_null($apituMember)) {
                throw new InvalidOperationException('apitu_member_not_found');
            }

            // get dpc
            $dpc = $this->dpcRepository->getByCode($apituMember->getDpcCode());

            if (is_null($dpc)) {
                throw new InvalidOperationException('dpc_not_found');
            }

            $phone = $apituMember->getPhone()->phone();

            // check if phone registered
            if ($this->userRepository->isPhoneExists($phone)) {
                throw new InvalidOperationException('phone_registered');
            }

            // create technician
            $address = new Address(
                $request->address,
                $request->area,
                $request->city,
                new ZipCode($request->zipCode),
                new Coordinate((float) $request->latitude, (float) $request->longitude)
            );

            $technicianAttributes = new Technician(
                $apituMember->getMemberId(),
                $dpc,
                $address,
                false
            );

            $technician = new User(
                new UserId(),
                $request->name,
                null,
                new Email($request->email, null),
                new Phone($phone, new Date(new \DateTime())),
                null,
                password_hash($request->password, PASSWORD_BCRYPT),
                User::STATUS_INACTIVE,
                [User::ROLE_TECHNICIAN],
                null,
                $technicianAttributes
            );

            // validate created technician
            $errors = $technician->validate();

            if (count($errors) > 0) {
                throw new ValidationException($errors);
            }

            // persist created technician
            return $this->userRepository->add($technician);
        } else {
            // get member from apitu service
            $apituMember = $this->apituService->getMemberByMemberId($request->apituMemberId);

            if (is_null($apituMember)) {
                throw new InvalidOperationException('apitu_member_not_found');
            }

            // create otp
            $otp = $this->otpService->generate($apituMember->getMemberId());
            $hash = $otp[1];
            $otp = $otp[0];

            // send otp
            $message = "A7Pro - Jangan memberitahukan kode rahasia ini kepada siapapun termasuk pihak A7Pro. Kode rahasia anda: {$otp}";

            if (!$this->smsService->send($apituMember->getPhone()->phone(), $message)) {
                throw new InvalidOperationException('cannot_send_otp_sms');
            }

            return [
                'signature' => $hash,
                'phone' => $apituMember->getPhone()->masked(),
                'otp' => $otp
            ];
        }
    }
}