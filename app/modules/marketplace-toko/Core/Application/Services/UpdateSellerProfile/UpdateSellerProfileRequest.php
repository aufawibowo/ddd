<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateSellerProfile;

class UpdateSellerProfileRequest
{
    public ?string $sellerId;
    public ?string $gender;
    public ?string $location;
    public ?string $placeOfBirth;
    public ?string $dateOfBirth;
    public ?float $latitude;
    public ?float $longitude;
    public ?string $description;
    public ?array $workingDays;
    public ?string $openingHours;
    public ?string $closingHours;
    public ?string $email;

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
        ?string $email
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
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if(!isset($this->gender))
            $errors[] = 'gender_must_be_specified';

        if(!isset($this->location))
            $errors[] = 'location_must_be_specified';

        if(!isset($this->placeOfBirth))
            $errors[] = 'place_of_birth_must_be_specified';

        if(!isset($this->dateOfBirth))
            $errors[] = 'date_of_birth_must_be_specified';

        if(!isset($this->latitude))
            $errors[] = 'latitude_must_be_specified';

        if(!isset($this->longitude))
            $errors[] = 'longitude_must_be_specified';

        if(!isset($this->description))
            $errors[] = 'description_must_be_specified';

        if(!isset($this->workingDays))
            $errors[] = 'working_days_must_be_specified';

        if(!isset($this->openingHours))
            $errors[] = 'opening_hours_must_be_specified';

        if(!isset($this->closingHours))
            $errors[] = 'closing_hours_must_be_specified';

        return $errors;
    }
}
