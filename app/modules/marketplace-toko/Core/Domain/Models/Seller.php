<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;

class Seller
{
    private ?string $sellerId;
    private ?string $gender;
    private ?string $location;
    private ?string $placeOfBirth;
    private ?string $dateOfBirth;
    private ?float $latitude;
    private ?float $longitude;
    private ?string $description;
    private ?array $workingDays;
    private ?string $openingHours;
    private ?string $closingHours;
    private ?string $email;
    private ?string $regency;
    private ?string $postalCode;
    private ?string $username;
    private ?string $password;
    private ?string $profilePict;
    private ?string $phone;
    private ?string $userFullName;
    private ?string $shopName;

    public function __construct(
        ?string $sellerId,
        ?string $gender,
        ?string $location,
        ?string $placeOfBirth,
        ?string $dateOfBirth,
        ?float $latitude,
        ?float $longitude,
        ?string $description,
        ?array $workingDays,
        ?string $openingHours,
        ?string $closingHours,
        ?string $email,
        ?string $regency,
        ?string $postalCode,
        ?string $username,
        ?string $password,
        ?string $profilePict,
        ?string $phone,
        ?string $userFullName,
        ?string $shopName
    ) {
        $this->sellerId = $sellerId;
        $this->gender = $gender;
        $this->location = $location;
        $this->placeOfBirth = $placeOfBirth;
        $this->dateOfBirth = $dateOfBirth;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->description = $description;
        $this->workingDays = $workingDays;
        $this->openingHours = $openingHours;
        $this->closingHours = $closingHours;
        $this->email = $email;
        $this->regency = $regency;
        $this->postalCode = $postalCode;
        $this->username = $username;
        $this->password = $password;
        $this->profilePict = $profilePict;
        $this->phone = $phone;
        $this->userFullName = $userFullName;
        $this->shopName = $shopName;
    }

    public function getSellerId(): ?string
    {
        return $this->sellerId;
    }

    public function getName(): ?string
    {
        return $this->userFullName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    public function getDateOfBirth(): ?string
    {
        return (new Date(new \DateTime($this->dateOfBirth)))->toDateOnly();
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getWorkingDays(): ?string
    {
        return json_encode($this->workingDays);
    }

    public function getOpeningHours(): ?string
    {
        return $this->openingHours;
    }

    public function getClosingHours(): ?string
    {
        return $this->closingHours;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getProfilePict(): ?string
    {
        return $this->profilePict;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRegency(): ?string
    {
        return $this->regency;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getShopName(): ?string
    {
        return $this->shopName;
    }

    public function validate(): array
    {
        $errors = [];

        return $errors;
    }
}
