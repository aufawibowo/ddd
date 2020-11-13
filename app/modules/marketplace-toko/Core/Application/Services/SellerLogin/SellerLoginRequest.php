<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SellerLogin;

class SellerLoginRequest
{
    public ?string $id;
    public ?string $otpSignature;
    public ?string $otp;
    public ?string $password;

    public function __construct(?string $id, ?string $otpSignature, ?string $otp, ?string $password)
    {
        $this->id = $id;
        $this->otpSignature = $otpSignature;
        $this->otp = $otp;
        $this->password = $password;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->id)) {
            $errors[] = 'id_must_be_specified';
        }

        return $errors;
    }
}