<?php

namespace A7Pro\Account\Core\Application\Services\TechnicianRegister;

class TechnicianRegisterRequest
{
    public ?string $apituMemberId;
    public ?string $otpSignature;
    public ?string $otp;
    public ?string $registrationUrl;
    public ?string $url;

    public ?string $name;
    public ?string $email;
    public ?string $password;
    public ?string $address;
    public ?string $area;
    public ?string $city;
    public ?string $zipCode;
    public ?string $latitude;
    public ?string $longitude;

    public function __construct(
        ?string $apituMemberId,
        ?string $otpSignature,
        ?string $otp,
        ?string $registrationUrl,
        ?string $url,
        ?string $name,
        ?string $email,
        ?string $password,
        ?string $address,
        ?string $area,
        ?string $city,
        ?string $zipCode,
        ?string $latitude,
        ?string $longitude
    ) {
        $this->apituMemberId = $apituMemberId;
        $this->otpSignature = $otpSignature;
        $this->otp = $otp;
        $this->registrationUrl = $registrationUrl;
        $this->url = $url;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->area = $area;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->apituMemberId)) {
            $errors[] = 'apitu_member_id_must_be_specified';
        }

        return $errors;
    }

    public function isVerifyOtp(): bool
    {
        return isset($this->otp) && isset($this->otpSignature);
    }

    public function isRegister(): bool
    {
        return isset($this->url)
            && isset($this->name)
            && isset($this->email)
            && isset($this->password)
            && isset($this->address)
            && isset($this->area)
            && isset($this->city)
            && isset($this->zipCode)
            && isset($this->latitude)
            && isset($this->longitude);
    }
}