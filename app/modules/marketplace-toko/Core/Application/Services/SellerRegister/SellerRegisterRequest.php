<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\SellerRegister;

use Phalcon\Http\Request\File;

class SellerRegisterRequest
{
    public ?string $phone;
    public ?string $otpSignature;
    public ?string $otp;
    public ?string $registrationUrl;
    public ?string $url;
    public ?string $type;

    public ?string $shopName;
    public ?string $userFullName;
    public ?string $email;
    public ?string $regency;
    public ?string $postalCode;
    public ?string $latitude;
    public ?string $longitude;
    public ?string $address;
    public ?string $username;
    public ?string $password;
    public ?string $passwordConfirmation;
    public ?string $description;
    public ?File $profilePict;

    public function __construct(
        ?string $phone,
        ?string $otpSignature,
        ?string $otp,
        ?string $registrationUrl,
        ?string $url,
        ?string $type,

        ?string $shopName,
        ?string $userFullName,
        ?string $email,
        ?string $regency,
        ?string $postalCode,
        ?string $latitude,
        ?string $longitude,
        ?string $address,
        ?string $username,
        ?string $password,
        ?string $passwordConfirmation,
        ?string $description,
        ?File $profilePict
    ) {
        $this->phone = $phone;
        $this->otpSignature = $otpSignature;
        $this->otp = $otp;
        $this->registrationUrl = $registrationUrl;
        $this->url = $url;
        $this->type = $type;

        $this->shopName = $shopName;
        $this->userFullName = $userFullName;
        $this->email = $email;
        $this->regency = $regency;
        $this->postalCode = $postalCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->address = $address;
        $this->username = $username;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
        $this->description = $description;
        $this->profilePict = $profilePict;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->phone))
            $errors[] = 'phone_must_be_specified';

        if($this->type == 'register'){
            if (!isset($this->shopName))
                $errors[] = 'shop_name_must_be_specified';
    
            if (!isset($this->userFullName))
                $errors[] = 'user_full_name_must_be_specified';
    
            if (!isset($this->email))
                $errors[] = 'email_must_be_specified';
    
            if (!isset($this->regency))
                $errors[] = 'regency_must_be_specified';
    
            if (!isset($this->postalCode))
                $errors[] = 'postal_code_must_be_specified';
    
            if (!isset($this->latitude))
                $errors[] = 'latitude_must_be_specified';
    
            if (!isset($this->longitude))
                $errors[] = 'longitude_must_be_specified';
    
            if (!isset($this->address))
                $errors[] = 'address_must_be_specified';
    
            if (!isset($this->username))
                $errors[] = 'username_must_be_specified';
    
            if (!isset($this->password))
                $errors[] = 'password_must_be_specified';
    
            if (!isset($this->passwordConfirmation))
                $errors[] = 'password_confirmation_must_be_specified';
    
            if ($this->password != $this->passwordConfirmation)
                $errors[] = 'password_confirmation_doesn\'t_match';
    
            if (!isset($this->description))
                $errors[] = 'description_must_be_specified';
    
            if ($this->profilePict) {
                $extensionsAllowed = ["image/jpg", "image/jpeg", "image/png"];
    
                if (!in_array($this->profilePict->getRealType(), $extensionsAllowed))
                    $errors[] = 'file_extension_not_allowed';
    
                if ($this->profilePict->getSize() > 5000000)
                    $errors[] = 'file_size_is_too_big';
            }
        }

        return $errors;
    }
}
