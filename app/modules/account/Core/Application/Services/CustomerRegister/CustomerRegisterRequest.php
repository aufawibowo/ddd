<?php

namespace A7Pro\Account\Core\Application\Services\CustomerRegister;

class CustomerRegisterRequest
{
    public ?string $phone;
    public ?string $otpSignature;
    public ?string $otp;
    public ?string $registrationUrl;
    public ?string $url;
    public ?string $name;
    public ?string $password;

    public function __construct(
        ?string $phone,
        ?string $otpSignature,
        ?string $otp,
        ?string $registrationUrl,
        ?string $url,
        ?string $name,
        ?string $password
    ) {
        $this->phone = $phone;
        $this->otpSignature = $otpSignature;
        $this->otp = $otp;
        $this->registrationUrl = $registrationUrl;
        $this->url = $url;
        $this->name = $name;
        $this->password = $password;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->phone)) {
            $errors[] = 'phone_must_be_specified';
        }

        return $errors;
    }
}